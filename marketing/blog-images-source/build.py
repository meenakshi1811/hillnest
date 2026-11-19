"""
Builds the blog cover images and in-article infographics.

Each image is an HTML page (brand colours, Google Fonts, inline SVG icons) rendered to PNG with
headless Chrome, then saved as JPG (covers, also used for social previews) or WebP (infographics)
in public/images/blog/.

    python marketing/blog-images-source/build.py

Edit the text in IMAGES below and run again to rebuild. The generated HTML files sit next to this
script so a single image can be opened and checked in a browser.
"""

import html
import os
import re
import shutil
import subprocess
import sys
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[2]
SRC = Path(__file__).resolve().parent
OUT = ROOT / 'public' / 'images' / 'blog'
CHROME_CANDIDATES = [
    r'C:\Program Files\Google\Chrome\Application\chrome.exe',
    r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe',
    shutil.which('google-chrome') or '',
    shutil.which('chromium') or '',
]

# ── Icons: 64×64 line art, drawn with currentColor ──────────────────────────────────────────────
ICONS = {
    'milk': '<path d="M23 8h18"/><path d="M25 8v7l-7 9v28a5 5 0 0 0 5 5h18a5 5 0 0 0 5-5V24l-7-9V8"/><path d="M18 33h28"/>',
    'curd': '<path d="M18 22h28"/><path d="M21 22c-6 4-9 9-9 16 0 10 9 18 20 18s20-8 20-18c0-7-3-12-9-16"/><path d="M23 14h18v8H23z"/><path d="M17 36c5 3 10-3 15 0s10 3 15 0"/>',
    'bilona': '<path d="M26 4h12"/><path d="M32 4v44"/><path d="M27 13l10 4M27 19l10 4"/><path d="M27 13c-6 0-11 2-14 6M37 23c6 0 11-2 14-6"/><path d="M15 34h34"/><path d="M17 34c-3 3-5 6-5 10 0 8 9 14 20 14s20-6 20-14c0-4-2-7-5-10"/><path d="M26 45l12 6M38 45l-12 6"/>',
    'fire': '<path d="M8 22h48"/><path d="M12 22a20 11 0 0 0 40 0"/><path d="M32 61c-7 0-10-5-8-10 1 3 4 3 4 3-2-6 3-9 4-13 3 5 9 8 7 15-1 3-4 5-7 5z"/>',
    'jar': '<rect x="19" y="7" width="26" height="8" rx="2"/><path d="M21 15v4c-5 2-7 6-7 10v22a6 6 0 0 0 6 6h24a6 6 0 0 0 6-6V29c0-4-2-8-7-10v-4"/><path d="M14 30h36M14 47h36"/>',
    'search': '<circle cx="27" cy="27" r="16"/><path d="M39 39l17 17"/><path d="M27 18c-5 6-7 9-7 12a7 7 0 0 0 14 0c0-3-2-6-7-12z"/>',
    'dropper': '<path d="M46 6l12 12"/><path d="M50 10 26 34l-3 8 8-3 24-24"/><path d="M16 46c-3 4-5 7-5 9a5 5 0 0 0 10 0c0-2-2-5-5-9z"/>',
    'fridge': '<rect x="17" y="5" width="30" height="54" rx="4"/><path d="M17 25h30M24 12v6M24 31v12"/>',
    'aroma': '<path d="M22 28c-4-4 4-8 0-13M32 28c-4-4 4-8 0-13M42 28c-4-4 4-8 0-13"/><path d="M10 36h44a22 14 0 0 1-44 0z"/>',
    'label': '<path d="M8 33 33 8h23v23L31 56z"/><circle cx="45" cy="19" r="4"/><path d="M24 38l6 6M29 33l9 9"/>',
    'snow': '<path d="M32 4v56M8 18l48 28M8 46l48-28"/><path d="M26 8l6 6 6-6M26 56l6-6 6 6M8 26l8-2-2-8M56 38l-8 2 2 8M8 38l8 2-2 8M56 26l-8-2 2-8"/>',
    'clock': '<circle cx="32" cy="32" r="24"/><path d="M32 18v15l10 6"/>',
    'hands': '<circle cx="32" cy="17" r="9"/><path d="M14 57c0-11 8-19 18-19s18 8 18 19"/>',
    'batch': '<path d="M8 30h48"/><path d="M12 30a20 12 0 0 0 40 0"/><path d="M24 22c-3-3 3-6 0-10M32 22c-3-3 3-6 0-10M40 22c-3-3 3-6 0-10"/><path d="M28 54h8"/>',
    'spoon': '<ellipse cx="21" cy="20" rx="10" ry="14" transform="rotate(-45 21 20)"/><path d="M29 28l26 26"/>',
    'lid': '<rect x="12" y="10" width="40" height="10" rx="3"/><path d="M16 20v28a6 6 0 0 0 6 6h20a6 6 0 0 0 6-6V20"/><path d="M24 36l6 6 12-12"/>',
    'sun': '<circle cx="32" cy="32" r="11"/><path d="M32 5v8M32 51v8M5 32h8M51 32h8M13 13l6 6M45 45l6 6M13 51l6-6M45 19l6-6"/>',
    'drop': '<path d="M32 6S14 27 14 40a18 18 0 0 0 36 0C50 27 32 6 32 6z"/>',
    'nose': '<path d="M14 26c4 3 9 3 13 0M37 26c4 3 9 3 13 0"/><path d="M32 30v10l-5 6h10"/><path d="M22 52c6 4 14 4 20 0"/>',
    'mould': '<rect x="14" y="14" width="36" height="42" rx="6"/><circle cx="26" cy="30" r="3"/><circle cx="38" cy="38" r="4"/><circle cx="28" cy="45" r="2"/><path d="M18 8h28"/>',
    'arrow': '<path d="M6 32h50M42 18l14 14-14 14"/>',
    'mountain': '<path d="M3 54 22 22l10 15 8-11 21 28z"/><path d="M18 30l4-8 5 8"/>',
}


def icon(name: str, size: int = 64, stroke: float = 2.6, cls: str = '') -> str:
    return (f'<svg class="{cls}" width="{size}" height="{size}" viewBox="0 0 64 64" fill="none" '
            f'stroke="currentColor" stroke-width="{stroke}" stroke-linecap="round" stroke-linejoin="round" '
            f'aria-hidden="true">{ICONS[name]}</svg>')


MOUNTAINS = (
    '<svg class="ridge" viewBox="0 0 1200 260" preserveAspectRatio="none" aria-hidden="true">'
    '<path d="M0 260 L0 170 L120 110 L210 150 L330 60 L430 135 L520 95 L640 170 L760 80 L880 150 '
    'L980 105 L1090 160 L1200 120 L1200 260Z" fill="currentColor" opacity=".10"/>'
    '<path d="M0 260 L0 205 L150 160 L280 200 L400 150 L560 210 L700 165 L860 215 L1000 175 '
    'L1200 210 L1200 260Z" fill="currentColor" opacity=".14"/></svg>'
)

BASE_CSS = """
:root { --gold:#C9973A; --gold-light:#E8C47A; --gold-pale:#F5E8C8; --forest:#1E3B2F;
        --forest-mid:#2D5240; --forest-light:#3E6B52; --cream:#FAF5EC; --cream-dark:#F0E8D6;
        --text:#2A1F14; --text-mid:#5C4A34; --text-light:#8A7560; }
* { box-sizing:border-box; margin:0; padding:0; }
html, body { width:WIDTHpx; height:HEIGHTpx; overflow:hidden; }
body { font-family:'Jost',sans-serif; -webkit-font-smoothing:antialiased; }
.eyebrow { font-size:15px; font-weight:600; letter-spacing:4px; text-transform:uppercase; color:var(--gold); }
.brand { font-family:'Cormorant Garamond',serif; font-style:italic; font-size:24px; }
.ridge { position:absolute; left:0; right:0; bottom:0; width:100%; height:260px; }
"""

COVER_CSS = """
body { position:relative; background:
  radial-gradient(circle at 78% 30%, rgba(232,196,122,.30), transparent 42%),
  linear-gradient(135deg, #1E3B2F 0%, #2D5240 60%, #3E6B52 100%); color:var(--cream); }
.ridge { color:var(--gold-light); }
.text { position:absolute; left:80px; top:0; bottom:0; width:600px; display:flex; flex-direction:column; justify-content:center; }
h1 { font-family:'Playfair Display',serif; font-weight:700; font-size:66px; line-height:1.08; margin:20px 0 22px; color:#FFF8EA; }
h1 em { color:var(--gold-light); font-style:italic; }
.sub { font-size:25px; line-height:1.45; color:rgba(250,245,236,.82); max-width:540px; }
.brand { position:absolute; left:80px; bottom:48px; color:var(--gold-light); }
.art { position:absolute; right:90px; top:50%; transform:translateY(-54%); width:400px; height:400px; border-radius:50%;
  background: radial-gradient(circle at 40% 35%, #FFF8EA, #F5E8C8 70%); box-shadow:0 30px 80px rgba(0,0,0,.28), inset 0 0 0 10px rgba(201,151,58,.25);
  display:flex; align-items:center; justify-content:center; gap:14px; color:var(--forest); }
.art .main { color:var(--forest); }
.art .accent { color:var(--gold); }
.art .arrow { color:var(--gold); }
.pill { position:absolute; padding:8px 16px; border-radius:99px; background:var(--forest); color:var(--gold-light); font-weight:600; font-size:18px; letter-spacing:1px; }
"""

INFO_CSS = """
html { background:#00FF00; }
body { height:auto !important; background:transparent; }
.page { position:relative; overflow:hidden; padding-bottom:110px; color:var(--text); background:
  radial-gradient(circle at 12% 0%, rgba(232,196,122,.35), transparent 40%), var(--cream); }
.ridge { color:var(--forest); height:120px; }
.wrap { position:relative; padding:56px 64px 0; }
h2 { font-family:'Playfair Display',serif; font-size:50px; font-weight:700; color:var(--forest); margin:10px 0 8px; line-height:1.15; }
h2 em { color:var(--gold); font-style:italic; }
.lead { font-size:24px; color:var(--text-mid); margin-bottom:34px; }
.foot { position:absolute; left:64px; right:64px; bottom:34px; display:flex; justify-content:space-between; align-items:center; font-size:21px; color:var(--text-mid); }
.foot .brand { color:var(--forest); }
.steps { display:flex; align-items:flex-start; gap:10px; }
.step { flex:1; text-align:center; }
.badge { position:relative; width:118px; height:118px; margin:0 auto 18px; border-radius:50%; background:#fff; color:var(--forest);
  display:flex; align-items:center; justify-content:center; box-shadow:0 14px 34px rgba(77,55,24,.12), inset 0 0 0 3px var(--gold-pale); }
.num { position:absolute; top:-6px; right:-6px; width:36px; height:36px; border-radius:50%; background:var(--gold); color:#fff; font-weight:700; font-size:18px; display:flex; align-items:center; justify-content:center; }
.step h3 { font-size:27px; font-weight:600; color:var(--forest); margin-bottom:8px; line-height:1.2; }
.step p { font-size:21px; line-height:1.4; color:var(--text-mid); padding:0 2px; }
.sep { width:40px; flex:0 0 40px; margin-top:44px; color:var(--gold); }
.grid { display:grid; gap:20px; }
.card { background:#fff; border-radius:22px; padding:24px 24px 22px; box-shadow:0 12px 32px rgba(77,55,24,.08); border:1px solid rgba(201,151,58,.18); }
.card .ico { width:64px; height:64px; border-radius:18px; background:var(--gold-pale); color:var(--forest); display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
.card h3 { font-size:27px; color:var(--forest); font-weight:600; margin-bottom:6px; line-height:1.2; }
.card p { font-size:21px; line-height:1.4; color:var(--text-mid); }
.note { margin-top:22px; padding:16px 22px; border-radius:14px; background:rgba(30,59,47,.07); color:var(--forest); font-size:21px; line-height:1.4; }
"""

FONTS = ('<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
         '<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700'
         '&family=Cormorant+Garamond:ital,wght@1,500&family=Jost:wght@400;500;600;700&display=block" rel="stylesheet">')


def page(width: int, height: int, css: str, body: str) -> str:
    base = BASE_CSS.replace('WIDTH', str(width)).replace('HEIGHT', str(height))
    return f'<!doctype html><html><head><meta charset="utf-8">{FONTS}<style>{base}{css}</style></head><body>{body}</body></html>'


def cover(eyebrow: str, title: str, sub: str, art: str) -> str:
    return page(1200, 750, COVER_CSS, f"""
      {MOUNTAINS}
      <div class="text"><p class="eyebrow">{eyebrow}</p><h1>{title}</h1><p class="sub">{html.escape(sub)}</p></div>
      <div class="art">{art}</div>
      <p class="brand">HillNest Journal · Upper Shimla</p>""")


INFO_WINDOW = 2400  # rendered tall, then cropped to the content (see main)


def info(_height: int, eyebrow: str, title: str, lead: str, inner: str, foot_left: str) -> str:
    return page(1200, INFO_WINDOW, INFO_CSS, f"""<div class="page">
      {MOUNTAINS}
      <div class="wrap"><p class="eyebrow">{eyebrow}</p><h2>{title}</h2><p class="lead">{html.escape(lead)}</p>{inner}</div>
      <div class="foot"><span>{html.escape(foot_left)}</span><span class="brand">hillnest.co</span></div></div>""")


def steps(items) -> str:
    out = []
    for i, (ico, head, text) in enumerate(items, 1):
        if i > 1:
            out.append(f'<div class="sep">{icon("arrow", 40, 3)}</div>')
        out.append(f'<div class="step"><div class="badge">{icon(ico, 72, 2.4)}<span class="num">{i}</span></div>'
                   f'<h3>{html.escape(head)}</h3><p>{html.escape(text)}</p></div>')
    return f'<div class="steps">{"".join(out)}</div>'


def cards(items, columns: int) -> str:
    out = ''.join(f'<div class="card"><div class="ico">{icon(ico, 48, 2.4)}</div><h3>{html.escape(head)}</h3><p>{html.escape(text)}</p></div>'
                  for ico, head, text in items)
    return f'<div class="grid" style="grid-template-columns:repeat({columns},1fr)">{out}</div>'


def art(*parts) -> str:
    return ''.join(parts)


# ── The images ──────────────────────────────────────────────────────────────────────────────────
IMAGES = {
    # what-is-bilona-ghee
    'what-is-bilona-ghee-cover.jpg': cover(
        'Ghee Guide', 'What is <em>bilona</em> ghee?',
        'How curd, a wooden churner and a slow fire turn milk into golden, grainy ghee.',
        art(icon('bilona', 230, 2.2, 'main'))),
    'bilona-process-steps.webp': info(
        640, 'The bilona way', 'From milk to ghee in <em>5 steps</em>',
        'How bilona ghee is made in village homes of Upper Shimla.',
        steps([
            ('milk', 'Fresh milk', 'Collected each morning from hill-grazing cows, then boiled'),
            ('curd', 'Curd overnight', 'Set overnight, traditionally in earthen pots'),
            ('bilona', 'Hand churning', 'A wooden bilona separates butter from buttermilk'),
            ('fire', 'Slow clarifying', 'Butter heated gently over a wood fire'),
            ('jar', 'Into the jar', 'Strained, cooled and sealed by hand'),
        ]),
        'About a day and a half from milk to jar'),

    # how-to-check-pure-ghee-at-home
    'how-to-check-pure-ghee-at-home-cover.jpg': cover(
        'Ghee Guide', 'Is your ghee <em>pure?</em>',
        'Five simple tests you can do in your kitchen, and what they can’t tell you.',
        art(icon('search', 230, 2.2, 'main'))),
    'home-ghee-purity-tests.webp': info(
        820, 'At a glance', '5 home tests for <em>pure ghee</em>',
        'Use them as warning signs, not as proof.',
        cards([
            ('fire', 'Heat test', 'Melts fast, turns clear golden, smells nutty. Pale, slow or oily is a warning sign.'),
            ('dropper', 'Iodine test', '2–3 drops of tincture of iodine on melted ghee. Blue means starch. Throw it away after.'),
            ('fridge', 'Fridge test', 'Melt in a glass jar and chill. Separate layers can point to another fat.'),
            ('nose', 'Smell & taste', 'Fresh and nutty is good. Sour, stale or "chemical" is not.'),
            ('label', 'Label check', 'FSSAI licence, full address, batch number, best-before date, simple ingredients.'),
            ('search', 'Still unsure?', 'An NABL-accredited food lab can test for vanaspati and oil blends.'),
        ], 3),
        'Iodine test from FSSAI’s DART booklet'),

    # ways-to-use-ghee-in-winter
    'ways-to-use-ghee-in-winter-cover.jpg': cover(
        'Recipes', 'Ghee for <em>winter</em>',
        'Seven traditional ways families in the hills cook with ghee when the cold sets in.',
        art(icon('snow', 150, 2.4, 'accent'), icon('aroma', 170, 2.2, 'main'))),
    'winter-ghee-dishes.webp': info(
        900, 'Winter kitchen', '7 winter dishes made <em>with ghee</em>',
        'Traditional favourites from North India and the hills of Himachal.',
        cards([
            ('aroma', 'Makki roti & sarson saag', 'Ghee on hot roti and in the saag tadka'),
            ('batch', 'Pinni & panjiri', 'Flour roasted slowly in ghee with jaggery'),
            ('fire', 'Gajar ka halwa', 'Cooked until the ghee separates at the edges'),
            ('drop', 'Himachali siddu', 'Steamed bun, torn open and drenched in ghee'),
            ('spoon', 'Til–gur laddoo', 'Made for Lohri and Makar Sankranti'),
            ('aroma', 'Moong dal khichdi', 'With a cumin and hing ghee tadka'),
            ('batch', 'Roasted makhana & nuts', 'A quick snack roasted in a spoon of ghee'),
            ('snow', 'Tip', 'Solid or grainy ghee in winter is normal'),
        ], 4),
        'Cook tadka on medium heat so the ghee doesn’t burn'),

    # why-is-bilona-ghee-expensive
    'why-is-bilona-ghee-expensive-cover.jpg': cover(
        'Ghee Guide', 'Why bilona ghee <em>costs more</em>',
        'More milk, more time and more hands go into every jar.',
        art(icon('milk', 150, 2.4, 'main'), icon('arrow', 60, 3, 'arrow'), icon('jar', 150, 2.4, 'main'))),
    'what-goes-into-one-jar.webp': info(
        620, 'Where the price comes from', 'What goes into <em>one jar</em>',
        'Why bilona ghee costs more than ghee made from cream.',
        cards([
            ('milk', 'More milk', 'Only part of the fat in curd becomes butter, so it needs far more milk'),
            ('clock', 'More time', 'About a day and a half, with curd set overnight'),
            ('hands', 'More hands', 'Churned and cooked by hand, not by machine'),
            ('batch', 'Small batches', 'A few kilograms at a time, over slow heat'),
            ('jar', 'Glass jars', 'Packed in glass and shipped with care'),
        ], 5),
        'A higher price alone doesn’t prove purity'),

    # how-to-store-ghee
    'how-to-store-ghee-cover.jpg': cover(
        'Ghee Guide', 'How to <em>store ghee</em>',
        'Keep it fresh for months, and know the signs it has gone bad.',
        art(icon('jar', 200, 2.2, 'main'), icon('spoon', 120, 2.4, 'accent'))),
    'ghee-storage-rules.webp': info(
        820, 'Keep it fresh', 'How to <em>store ghee</em>',
        'Four rules for storing ghee, and four signs it has gone bad.',
        cards([
            ('lid', 'Lid closed', 'Air slowly makes fats stale'),
            ('spoon', 'Dry spoon', 'Water is the main reason ghee spoils'),
            ('sun', 'Away from heat', 'Not next to the stove or in the sun'),
            ('jar', 'Glass or steel', 'They don’t hold on to smells'),
        ], 4)
        + '<p class="eyebrow" style="margin:30px 0 14px">Signs it has gone bad</p>'
        + cards([
            ('nose', 'Smell', 'Sour, stale or paint-like'),
            ('drop', 'Taste', 'Bitter or sharp'),
            ('mould', 'Mould', 'Usually from water in the jar'),
            ('search', 'Colour', 'White patches or dark spots'),
        ], 4),
        'Grainy, solid in winter, liquid in summer: all normal'),

    # a2-ghee-vs-regular-ghee
    'a2-ghee-vs-regular-ghee-cover.jpg': cover(
        'Ghee Guide', '<em>A2</em> ghee vs regular ghee',
        'What A1 and A2 mean, and why the label matters less in ghee than in milk.',
        art('<div style="position:relative">' + icon('drop', 150, 2.4, 'main') + '<span class="pill" style="left:34px;top:84px">Milk</span></div>',
            icon('arrow', 56, 3, 'arrow'),
            '<div style="position:relative">' + icon('jar', 150, 2.4, 'main') + '<span class="pill" style="left:32px;top:84px">Ghee</span></div>')),
}

# Milk → butter → ghee, with approximate composition bars (typical textbook values).
_BARS = [
    ('Cow milk', [('Water', 87, '#BFD3C6'), ('Fat', 4, '#C9973A'), ('Protein', 3.5, '#6B4F30'), ('Lactose & minerals', 5.5, '#E8C47A')]),
    ('Butter', [('Water', 16, '#BFD3C6'), ('Fat', 82, '#C9973A'), ('Protein', 1, '#6B4F30'), ('Lactose & minerals', 1, '#E8C47A')]),
    ('Ghee', [('Fat', 99.5, '#C9973A'), ('Water, protein (traces)', 0.5, '#6B4F30')]),
]


def _bars() -> str:
    rows = []
    for name, parts in _BARS:
        segs = ''.join(f'<div style="flex:{pct};background:{col}" title="{label}"></div>' for label, pct, col in parts)
        rows.append(f'<div style="display:flex;align-items:center;gap:20px;margin-bottom:22px">'
                    f'<div style="width:150px;font-size:27px;font-weight:600;color:#1E3B2F">{name}</div>'
                    f'<div style="flex:1;display:flex;height:54px;border-radius:14px;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(30,59,47,.12)">{segs}</div></div>')
    legend = ''.join(f'<span style="display:inline-flex;align-items:center;gap:8px;margin-right:26px;font-size:21px;color:#5C4A34">'
                     f'<i style="width:18px;height:18px;border-radius:5px;background:{c};display:inline-block"></i>{l}</span>'
                     for l, c in [('Water', '#BFD3C6'), ('Fat', '#C9973A'), ('Protein (incl. beta-casein)', '#6B4F30'), ('Lactose & minerals', '#E8C47A')])
    return (''.join(rows) + f'<div style="margin:6px 0 0 170px">{legend}</div>'
            + '<p class="note">Most milk protein, including the A1 or A2 beta-casein, stays in the milk solids that are strained out when butter becomes ghee.</p>')


IMAGES['what-ends-up-in-ghee.webp'] = info(
    650, 'A1 vs A2', 'What ends up <em>in ghee?</em>',
    'Approximate composition from milk to butter to ghee.',
    _bars(),
    'Typical values; real numbers vary with the milk')


def find_chrome() -> str:
    for path in CHROME_CANDIDATES:
        if path and os.path.isfile(path):
            return path
    sys.exit('Chrome or Edge not found.')


def main() -> None:
    chrome = find_chrome()
    OUT.mkdir(parents=True, exist_ok=True)
    only = set(sys.argv[1:])

    for name, markup in IMAGES.items():
        if only and name not in only:
            continue

        size = re.search(r'html, body \{ width:(\d+)px; height:(\d+)px', markup)
        width, height = int(size.group(1)), int(size.group(2))
        html_path = SRC / (Path(name).stem + '.html')
        png_path = SRC / (Path(name).stem + '.png')
        html_path.write_text(markup, encoding='utf-8')

        subprocess.run([
            chrome, '--headless=new', '--disable-gpu', '--hide-scrollbars', '--force-device-scale-factor=1',
            f'--window-size={width},{height}', '--virtual-time-budget=10000',
            f'--screenshot={png_path}', html_path.as_uri(),
        ], check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

        image = Image.open(png_path).convert('RGB')
        if 'class="page"' in markup:
            height = next((y for y in range(image.height) if image.getpixel((3, y)) == (0, 255, 0)), image.height)
        image = image.crop((0, 0, width, height))
        target = OUT / name
        if name.endswith('.jpg'):
            image.save(target, quality=86, optimize=True, progressive=True)
        else:
            image.save(target, quality=88, method=6)
        png_path.unlink()
        print(f'{target.relative_to(ROOT)}  {width}x{height}  {target.stat().st_size // 1024} KB')


if __name__ == '__main__':
    main()
