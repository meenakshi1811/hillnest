<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

/**
 * Starter SEO articles for the blog.
 *
 * Run on its own: php artisan db:seed --class=BlogPostSeeder
 * (DatabaseSeeder also resets the admin login and products, so don't run that on the live site.)
 *
 * Every article is created as a draft so the team can check the facts, add their own photos and
 * publish from Admin → Blog. Existing articles (matched by slug) are never overwritten, so it is
 * safe to run again after editing.
 */
class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $post) {
            $model = BlogPost::firstOrCreate(
                ['slug' => $post['slug']],
                array_merge($post, [
                    'author_name' => 'HillNest',
                    'is_published' => false,
                    'published_at' => null,
                ])
            );

            // An article seeded before covers existed gets one, without touching anything else.
            if (! $model->wasRecentlyCreated && ! $model->cover_image) {
                $model->update(['cover_image' => $post['cover_image'], 'cover_image_alt' => $post['cover_image_alt']]);
            }
        }
    }

    private function posts(): array
    {
        return [
            [
                'slug' => 'what-is-bilona-ghee',
                'title' => 'What Is Bilona Ghee? How It Is Made and Why It Tastes Different',
                'category' => 'Ghee Guide',
                'meta_title' => 'What Is Bilona Ghee? Process, Taste & How It Differs',
                'meta_description' => 'Bilona ghee is made by churning curd, not cream. See the 5-step village process, how it differs from regular ghee, and why it turns grainy and golden.',
                'excerpt' => 'Bilona ghee is made from curd churned by hand into butter, then slowly clarified. Here is the full village process, step by step, and how it differs from the ghee on most shop shelves.',
                'cover_image' => 'images/blog/what-is-bilona-ghee-cover.jpg',
                'cover_image_alt' => 'Illustration of a wooden bilona churner in a pot, with the title What is bilona ghee?',
                'body' => <<<'MD'
Ask anyone who grew up in a Himachali village what real ghee tastes like and they will describe the same thing: a nutty smell that fills the kitchen, a grainy texture, and a deep golden colour. That ghee is almost always **bilona ghee**.

"Bilona" is the name of the wooden churner (also called *madhani* or *rai*) used to churn curd into butter. Bilona ghee is simply ghee made the slow way: milk becomes curd, curd becomes butter, and butter becomes ghee.

## How bilona ghee is made, step by step

This is how we make it in Chhajpur, Upper Shimla, the same way our families always have.

1. **Fresh milk.** Milk is collected in the morning from cows that graze freely on hill pastures, then boiled.
2. **Curd, set overnight.** Once the milk cools, a spoon of the previous day's curd is added and it is left to set overnight, traditionally in earthen pots.
3. **Hand churning.** The next morning the curd is churned with a wooden bilona and a little water. Slowly the butter (*makkhan*) separates and floats on top, leaving buttermilk (*chhaach*) below.
4. **Slow clarifying over a wood fire.** The butter is heated gently in small batches. The water evaporates, the milk solids settle and turn golden-brown, and the clear ghee rises above them.
5. **Straining and packing.** The ghee is strained and left to cool before it is sealed in jars.

The whole process takes about a day and a half from milk to jar, and most of it cannot be rushed.

![The bilona process in five steps: fresh milk, curd set overnight, hand churning with a wooden bilona, slow clarifying over a wood fire, and sealing in jars](/images/blog/bilona-process-steps.webp)

## Bilona ghee vs regular ghee

Most ghee sold in India is made from **cream (malai)**, not curd. In factories, cream is separated from milk by machine and heated directly into ghee. It is faster and uses less labour, which is why it costs less.

| | Bilona ghee | Cream-based ghee |
|---|---|---|
| Made from | Curd, churned into butter | Cream separated from milk |
| Churning | Wooden bilona, usually by hand | Machine, or no churning at all |
| Heating | Slow, small batches | Fast, large batches |
| Texture | Usually grainy (*danedar*) | Often smooth |
| Aroma | Strong, nutty | Milder |

Neither method adds anything to the ghee. The difference is in time, scale and the flavour that slow fermentation and slow heating create.

## Why bilona ghee is grainy and golden

**The grains** form when ghee cools slowly. Different fats in the ghee set at different temperatures and form small crystals. That is why bilona ghee often looks *danedar* in winter and turns liquid in summer. Both are normal.

**The colour and aroma** come from the milk solids that toast gently at the bottom of the pan while the butter is clarified. Cows that graze on fresh green grass also tend to give milk with a more golden fat.

## Does bilona ghee taste different?

Yes, most people notice it straight away. Bilona ghee has a stronger, slightly caramel-like aroma and a richer taste, so a smaller amount flavours a dish. Try a spoon on hot rice with dal, or brushed on a fresh roti, and compare it with the ghee you usually buy.

## How to choose good bilona ghee

- **Look for the word "bilona" and the process.** A brand should tell you whether the ghee is made from curd or cream.
- **Check the FSSAI licence number** and the best-before date on the label.
- **Smell it.** Good ghee smells nutty and fresh, never sour or stale.
- **Expect grains.** Grainy texture is a good sign, not a fault.

Want to check ghee at home? Read our guide on [how to check if ghee is pure](/blog/how-to-check-pure-ghee-at-home). Curious about price? We explain [why bilona ghee costs more](/blog/why-is-bilona-ghee-expensive).

## Try HillNest bilona ghee

Our ghee is made in small batches in village homes of Upper Shimla, exactly as described above. If you are trying bilona ghee for the first time, start with the [250 g jar](/shop/hill-nest-pure-bilona-ghee-250gm); families who cook with ghee every day usually pick the [500 g](/shop/hill-nest-pure-bilona-ghee-500gm) or [1 kg](/shop/hill-nest-pure-bilona-ghee-1kg) jar.
MD,
                'faqs' => [
                    ['question' => 'What is the difference between bilona ghee and desi ghee?', 'answer' => '"Desi ghee" usually just means ghee made from cow or buffalo milk. Bilona ghee is desi ghee made a specific way: curd is churned into butter with a wooden bilona and then slowly clarified.'],
                    ['question' => 'Why is bilona ghee grainy?', 'answer' => 'Ghee forms small crystals when it cools slowly, because its fats set at different temperatures. Grainy (danedar) texture is normal and often a sign of slow-made ghee.'],
                    ['question' => 'Is bilona ghee made from curd or cream?', 'answer' => 'From curd. Milk is set into curd, the curd is churned into butter, and the butter is clarified into ghee. Most regular ghee is made directly from cream.'],
                    ['question' => 'Does bilona ghee need to be refrigerated?', 'answer' => 'No. Keep it in a closed jar in a cool, dry place away from sunlight, and always use a clean, dry spoon.'],
                ],
            ],

            [
                'slug' => 'how-to-check-pure-ghee-at-home',
                'title' => 'How to Check if Ghee Is Pure at Home: 5 Simple Tests',
                'category' => 'Ghee Guide',
                'meta_title' => 'How to Check Pure Ghee at Home: 5 Simple Tests',
                'meta_description' => 'Simple home tests to spot adulterated ghee, including the FSSAI iodine test for starch, plus what home tests cannot catch and what to check on the label.',
                'excerpt' => 'Worried your ghee might be adulterated? These five kitchen tests can flag common problems, and here is what they cannot tell you.',
                'cover_image' => 'images/blog/how-to-check-pure-ghee-at-home-cover.jpg',
                'cover_image_alt' => 'Illustration of a magnifying glass over a drop of ghee, with the title Is your ghee pure?',
                'body' => <<<'MD'
Ghee is one of the most commonly adulterated foods in India. Cheaper fats such as vanaspati, other vegetable oils, or starches like mashed potato are sometimes mixed in to cut costs.

You cannot run a lab at home, but a few simple tests can flag obvious problems. Use them as **warning signs**, not as proof.

![Five home tests for pure ghee at a glance: heat test, iodine test, fridge test, smell and taste, and label check](/images/blog/home-ghee-purity-tests.webp)

## 1. The heat test

Put a teaspoon of ghee in a small pan on low heat.

- **Pure ghee** melts quickly, turns clear golden, and gives off a nutty smell.
- **Warning sign:** it takes long to melt, stays pale or cloudy, foams a lot, or smells like oil instead of ghee.

## 2. The iodine test for starch

This test comes from FSSAI's *DART* booklet (Detect Adulteration with Rapid Test) and checks for starches such as mashed potato or sweet potato.

1. Take half a teaspoon of ghee in a small glass bowl and melt it.
2. Add two or three drops of tincture of iodine (available at chemists).
3. **Blue colour** means starch is present. Pure ghee does not turn blue.

Tincture of iodine is for external use only, so throw the tested sample away.

## 3. The fridge (jar) test

Melt some ghee in a clear glass jar, close it and put it in the fridge for 30–60 minutes.

- **Pure ghee** sets fairly evenly.
- **Warning sign:** clear separate layers. Different fats set at different temperatures, so distinct layers can point to another fat, such as coconut oil, being mixed in.

## 4. The smell and taste test

Rub a little ghee between your palms or taste a small amount.

- Good ghee smells nutty and fresh and tastes clean.
- A sour, stale or "chemical" smell, or a very strong artificial aroma, is a warning sign. Some low-quality ghee uses added flavour to hide cheap fats.

## 5. The label test

Before you even open the jar, check:

- An **FSSAI licence number** and full manufacturer address
- **Ingredients**: ghee should simply be milk fat. Be careful with words like "flavoured", "blended" or "with added aroma".
- **Batch number and best-before date**
- Whether the brand explains **how** the ghee is made (curd or cream, bilona or machine)

## What home tests cannot tell you

Home tests are useful, but they have real limits:

- **They can't reliably detect vanaspati or vegetable oil blends** in small amounts. Labs use tests such as the RM value and BR reading to check fat composition.
- **They don't check quality issues** like rancidity in their early stages, or contamination.
- **Popular "tests" are often unreliable.** For example, whether ghee floats or sinks in water tells you very little.

If you have a serious doubt, a food-testing lab accredited by NABL can test a sample. You can also ask the brand for a recent lab report.

## How we keep our ghee honest

At HillNest our ghee is made in small batches in village homes in Upper Shimla: curd churned in a wooden bilona and butter slowly clarified over a wood fire. Nothing else is added. Read more about [what bilona ghee is](/blog/what-is-bilona-ghee) or see [our story](/about).
MD,
                'faqs' => [
                    ['question' => 'Can I check ghee purity in water?', 'answer' => 'Not reliably. Whether ghee floats or sinks in water depends mostly on temperature, so it does not tell you much about adulteration.'],
                    ['question' => 'How do you test ghee for starch?', 'answer' => 'Melt half a teaspoon of ghee, add two or three drops of tincture of iodine, and look for a blue colour. Blue means starch is present. Throw the sample away afterwards.'],
                    ['question' => 'Can home tests detect vanaspati in ghee?', 'answer' => 'Not reliably, especially in small amounts. A food-testing lab can check fat composition with tests such as the RM value and BR reading.'],
                    ['question' => 'What should I check on a ghee label?', 'answer' => 'An FSSAI licence number, full manufacturer address, a simple ingredient list (milk fat only), batch number, best-before date, and how the ghee is made.'],
                ],
            ],

            [
                'slug' => 'ways-to-use-ghee-in-winter',
                'title' => '7 Traditional Ways to Use Ghee in Winter',
                'category' => 'Recipes',
                'meta_title' => '7 Traditional Ways to Use Ghee in Winter',
                'meta_description' => 'From makki ki roti and sarson ka saag to pinni, gajar halwa and Himachali siddu, seven traditional winter dishes made with ghee, plus simple tips.',
                'excerpt' => 'In the hills, winter cooking and ghee go together. Here are seven traditional ways families across North India use ghee when the cold sets in.',
                'cover_image' => 'images/blog/ways-to-use-ghee-in-winter-cover.jpg',
                'cover_image_alt' => 'Illustration of a snowflake and a steaming bowl, with the title Ghee for winter',
                'body' => <<<'MD'
In Upper Shimla, winter mornings start below freezing, and the kitchen is the warmest room in the house. Ghee has always been part of winter cooking here: warm, rich dishes for cold days.

Here are seven traditional ways to use ghee this winter.

![Seven winter dishes made with ghee: makki roti with sarson saag, pinni and panjiri, gajar ka halwa, Himachali siddu, til-gur laddoo, moong dal khichdi, and roasted makhana](/images/blog/winter-ghee-dishes.webp)

## 1. On makki ki roti with sarson ka saag

The classic North Indian winter meal. A generous spoon of ghee melting on hot makki ki roti, served with slow-cooked sarson ka saag, is hard to beat. Add a little ghee to the saag tadka too, with garlic and green chilli.

## 2. In pinni and panjiri

Pinni (whole-wheat flour roasted in ghee with jaggery, nuts and seeds) and panjiri are made in big batches in many homes as the cold begins. Roast the flour slowly in ghee on low heat until it smells nutty. This step decides the flavour, so don't rush it.

## 3. In gajar ka halwa

Winter brings the sweet red carrots that make the best gajar ka halwa. Cook grated carrots in milk until thick, then add ghee and sugar and keep stirring until the ghee starts to separate at the edges.

## 4. With Himachali siddu

Siddu is a soft, steamed wheat bun from Himachal, usually stuffed with a walnut, poppy seed or lentil filling. It is always served the same way: torn open and drenched in ghee. Read more about our village in [our story](/about).

## 5. In til and gur laddoos

Around Lohri and Makar Sankranti, sesame seeds (*til*) and jaggery (*gur*) laddoos are made in homes across North India. A little ghee helps bind them and stops the jaggery from sticking to your hands.

## 6. On khichdi

A bowl of moong dal khichdi with a spoon of ghee on top is the simplest comfort food on a cold night. Temper the ghee with cumin, a pinch of hing and a dry red chilli, and pour it over just before serving.

## 7. For roasting dry fruits and makhana

Lightly roast almonds, cashews or makhana in a spoon of ghee with a pinch of salt or black pepper for a quick winter snack.

## Tips for cooking with ghee

- **Heat gently for tadka.** Ghee handles heat well, but burnt ghee tastes bitter. Medium heat is enough.
- **A little goes a long way.** Bilona ghee has a stronger aroma, so you may need less than you think.
- **Ghee sets in winter.** Solid or grainy ghee in cold weather is normal. Scoop it with a dry spoon or let it soften near the stove. [Here is how to store ghee properly](/blog/how-to-store-ghee).

Planning a winter of slow cooking? Our [1 kg jar](/shop/hill-nest-pure-bilona-ghee-1kg) is the most practical size for families, and the [500 g jar](/shop/hill-nest-pure-bilona-ghee-500gm) suits smaller homes.
MD,
                'faqs' => [
                    ['question' => 'Why does ghee become solid in winter?', 'answer' => 'Ghee sets at low temperatures because of the fats it contains. Solid or grainy ghee in winter is normal and does not mean it has gone bad.'],
                    ['question' => 'What is siddu?', 'answer' => 'Siddu is a traditional Himachali steamed wheat bun, usually stuffed with walnuts, poppy seeds or lentils, and served with plenty of ghee.'],
                    ['question' => 'Can I use ghee for tadka on high heat?', 'answer' => 'Ghee handles heat well, but medium heat is enough for tadka. Very high heat can burn the ghee and make it taste bitter.'],
                ],
            ],

            [
                'slug' => 'why-is-bilona-ghee-expensive',
                'title' => 'Why Is Bilona Ghee Expensive? What Goes Into One Jar',
                'category' => 'Ghee Guide',
                'meta_title' => 'Why Is Bilona Ghee Expensive? What Goes Into a Jar',
                'meta_description' => 'Bilona ghee costs more because of how much milk it needs, the overnight curd, hand churning and slow small-batch cooking. Here is what goes into one jar.',
                'excerpt' => 'Bilona ghee often costs two to three times more than regular ghee. Here is where that price comes from, and how to make sure you are paying for the real thing.',
                'cover_image' => 'images/blog/why-is-bilona-ghee-expensive-cover.jpg',
                'cover_image_alt' => 'Illustration of a milk can and a ghee jar, with the title Why bilona ghee costs more',
                'body' => <<<'MD'
If you have compared ghee prices online, you have seen the gap: bilona ghee usually costs far more than the ghee in most grocery stores. It's a fair question to ask why.

The short answer: **bilona ghee needs more milk, more time and more hands.** Here is the longer answer.

![What goes into one jar of bilona ghee: more milk, more time, more hands, small batches and glass jars](/images/blog/what-goes-into-one-jar.webp)

## 1. It takes a lot of milk

Bilona ghee is made from curd, and only part of the milk fat ends up as butter after churning. Village makers commonly say it takes roughly **25 to 30 litres of milk to make one kilogram of bilona ghee**, depending on the milk and the season. Cream-based ghee loses less fat along the way, so it needs less milk for the same amount.

When milk is the biggest cost, needing more of it changes the price a lot.

## 2. It takes time

From fresh milk to finished ghee, the bilona process takes about a day and a half:

- Milk is boiled, cooled and **set into curd overnight**.
- The curd is **churned by hand** with a wooden bilona until butter separates.
- The butter is **clarified slowly over low heat** in small batches.

None of these steps can be sped up without changing the ghee.

## 3. It is made by hand, in small batches

Factories can produce tonnes of ghee a day with machines. Bilona ghee is usually churned and cooked by hand, a few kilograms at a time. That means more people and more hours for every jar.

## 4. Where the milk comes from matters

Cows that graze freely on hill pastures generally give less milk than high-yield dairy cows fed in sheds. Less milk per cow means more cows, more care and more cost for the same amount of ghee.

## 5. Packing and delivery

Good ghee is usually packed in glass jars, which cost more than plastic tubs and need careful packing for delivery across India.

## Does a higher price guarantee pure ghee?

No. **Price alone does not prove purity.** Some brands charge bilona prices for ghee that isn't made the bilona way. Before you buy, check:

- That the brand explains its process clearly (curd or cream, hand-churned or machine)
- An FSSAI licence number and full address on the label
- The smell, texture and colour once it arrives

You can also try a few [simple purity tests at home](/blog/how-to-check-pure-ghee-at-home).

## Is bilona ghee worth it?

That depends on what you value. Many families use bilona ghee for the dishes where ghee is the star (on rotis, dal, khichdi and sweets) and regular cooking oil elsewhere. Because its aroma is stronger, a little goes a long way.

If you'd like to try it without committing to a big jar, start with our [250 g jar](/shop/hill-nest-pure-bilona-ghee-250gm). Read [how bilona ghee is made](/blog/what-is-bilona-ghee) to see every step.
MD,
                'faqs' => [
                    ['question' => 'How much milk is needed to make 1 kg of bilona ghee?', 'answer' => 'Village makers commonly say roughly 25 to 30 litres of milk, depending on the milk and the season. Cream-based ghee needs less.'],
                    ['question' => 'Why is bilona ghee costlier than regular ghee?', 'answer' => 'It needs more milk, an overnight curd stage, hand churning and slow small-batch cooking, all of which add cost compared with machine-made cream ghee.'],
                    ['question' => 'Is expensive ghee always pure?', 'answer' => 'No. Check that the brand explains its process, has an FSSAI licence and full address on the label, and that the ghee smells fresh and nutty.'],
                ],
            ],

            [
                'slug' => 'how-to-store-ghee',
                'title' => 'How to Store Ghee: Shelf Life, Fridge or Not, and Signs It Has Gone Bad',
                'category' => 'Ghee Guide',
                'meta_title' => 'How to Store Ghee: Shelf Life & Signs It Has Gone Bad',
                'meta_description' => 'Does ghee need a fridge? How long does it last? Simple rules to keep ghee fresh, why it turns grainy or solid, and the signs that ghee has gone bad.',
                'excerpt' => 'Ghee keeps well for months if you follow a few simple rules. Here is how to store it, whether it needs the fridge, and how to tell when it has gone bad.',
                'cover_image' => 'images/blog/how-to-store-ghee-cover.jpg',
                'cover_image_alt' => 'Illustration of a ghee jar and a dry spoon, with the title How to store ghee',
                'body' => <<<'MD'
Ghee is one of the longest-lasting foods in an Indian kitchen. Because the water and milk solids are removed while it is made, there is very little left for bacteria to grow on. But it can still go stale if it is stored badly.

![How to store ghee: keep the lid closed, use a dry spoon, keep it away from heat, and use glass or steel. Signs it has gone bad: sour smell, bitter taste, mould, unusual colour](/images/blog/ghee-storage-rules.webp)

## The 4 rules for storing ghee

1. **Keep the lid closed.** Air slowly makes fats stale. Close the jar after every use.
2. **Always use a dry, clean spoon.** Water or food crumbs in the jar are the most common reason ghee spoils early.
3. **Keep it away from heat and sunlight.** Don't store the jar right next to the stove or on a sunny windowsill.
4. **Use a glass or steel container.** Glass and steel don't absorb smells. If you move ghee into another container, make sure it is completely dry.

## Does ghee need to be refrigerated?

**No.** Ghee is fine at room temperature in a cool, dry cupboard. You can refrigerate it if your kitchen gets very hot, but it will become hard. Take out what you need a little before cooking.

If you buy a big jar, a good habit is to keep a small jar for daily use and leave the big one closed in a cool place.

## How long does ghee last?

Always go by the **best-before date on the jar**. Unopened ghee stored well keeps for many months. Once opened, ghee kept with a dry spoon in a closed jar generally stays good for several months, but it is best enjoyed fresh, when its aroma is strongest.

## Why ghee changes texture

- **Grainy (*danedar*) ghee** is normal. It forms when ghee cools slowly.
- **Solid in winter, liquid in summer** is also normal. Ghee melts at warm room temperature and sets when it's cold.
- **Layers** of liquid and grainy ghee can form when the temperature goes up and down. Stir it, or melt and let it set again.

None of these mean the ghee has gone bad.

## Signs your ghee has gone bad

- A **sour, stale or paint-like smell** instead of a nutty one
- A **bitter or sharp taste**
- **Mould**, usually because water got into the jar
- **Unusual colour**, such as very pale, white patches, or very dark spots

If you notice any of these, don't use it.

## Can you freeze ghee?

You can, but there is rarely a need. If you buy ghee in bulk and live somewhere very hot, freezing a closed portion can help it keep longer.

## Buying the right size

The fresher your ghee, the better it tastes. Pick a size you'll finish comfortably: our [250 g jar](/shop/hill-nest-pure-bilona-ghee-250gm) for small homes, [500 g](/shop/hill-nest-pure-bilona-ghee-500gm) for daily cooking, and [1 kg](/shop/hill-nest-pure-bilona-ghee-1kg) for bigger families. Want to know why bilona ghee turns grainy? Read [what bilona ghee is](/blog/what-is-bilona-ghee).
MD,
                'faqs' => [
                    ['question' => 'Should ghee be kept in the fridge?', 'answer' => 'It does not need to be. A closed jar in a cool, dry cupboard is enough. Refrigerating is fine in very hot weather, but the ghee will turn hard.'],
                    ['question' => 'How do I know if ghee has gone bad?', 'answer' => 'Bad ghee smells sour or stale instead of nutty, tastes bitter, or shows mould or unusual colour. If you notice any of these, throw it away.'],
                    ['question' => 'Why is my ghee grainy?', 'answer' => 'Graininess forms when ghee cools slowly. It is normal and does not mean the ghee is spoiled.'],
                    ['question' => 'Can water spoil ghee?', 'answer' => 'Yes. Water in the jar can lead to mould and make ghee go bad faster. Always use a dry spoon.'],
                ],
            ],

            [
                'slug' => 'a2-ghee-vs-regular-ghee',
                'title' => 'A2 Ghee vs Regular Ghee: What Is the Real Difference?',
                'category' => 'Ghee Guide',
                'meta_title' => 'A2 Ghee vs Regular Ghee: What Is the Real Difference?',
                'meta_description' => 'What do A1 and A2 mean, why the difference matters less in ghee than in milk, and what actually decides the quality of the ghee you buy.',
                'excerpt' => 'A2 is printed on more and more ghee jars. Here is what it means, why it matters less for ghee than most labels suggest, and what you should look at instead.',
                'cover_image' => 'images/blog/a2-ghee-vs-regular-ghee-cover.jpg',
                'cover_image_alt' => 'Illustration of a milk drop turning into a ghee jar, with the title A2 ghee vs regular ghee',
                'body' => <<<'MD'
"A2 ghee" is one of the most common phrases on ghee jars today. But what does A2 actually mean, and does it make a difference in ghee? Here is a plain explanation.

## What A1 and A2 mean

Milk contains proteins called **beta-casein**. There are two common types:

- **A2 beta-casein**, found in milk from most traditional Indian (desi) cow breeds, and in buffalo, goat and human milk
- **A1 beta-casein**, common in many high-yield European-origin breeds and their cross-breeds

So "A2 milk" means milk from cows whose milk contains only the A2 type. "A2 ghee" means ghee made from that milk.

## Why the difference matters less in ghee

Here is the part most labels don't mention: **ghee is almost entirely fat.** When butter is clarified, the milk proteins (including beta-casein) are mostly left behind in the toasted solids that are strained out.

So while the A1 and A2 question is about milk *protein*, the ghee in your jar contains only traces of protein. The A1 or A2 label tells you about the cows, but it is not the main thing that decides how good the ghee is.

![Approximate composition of cow milk, butter and ghee: milk is mostly water, butter is mostly fat, and ghee is almost entirely fat with only traces of protein](/images/blog/what-ends-up-in-ghee.webp)

## What actually decides ghee quality

If you want good ghee, these matter more than the A1 or A2 label:

1. **The process.** Ghee made the [bilona way](/blog/what-is-bilona-ghee), from curd churned into butter and slowly clarified, has a fuller aroma and flavour than fast, machine-made cream ghee.
2. **What the cows eat.** Cows that graze on fresh grass and pasture tend to give milk fat with a deeper colour and flavour.
3. **Purity.** Nothing added: no vanaspati, no oils, no added flavour. See [how to check ghee at home](/blog/how-to-check-pure-ghee-at-home).
4. **Freshness and storage.** Fresh ghee in a clean, closed jar tastes best. Here's [how to store ghee](/blog/how-to-store-ghee).

## So, is A2 ghee worth it?

A2 ghee from desi cows is often made in smaller batches, in traditional ways, by people who care about the process, and that is usually what you are really paying for. When you compare brands, look past the "A2" label and ask:

- How is the ghee made: from curd or cream? By hand or machine?
- Where do the cows graze, and what do they eat?
- Does the label show an FSSAI licence, batch number and best-before date?

## Our ghee

At HillNest, our ghee comes from desi cows that graze freely on high-altitude pastures in Upper Shimla. Their milk is set into curd, churned in a wooden bilona and slowly clarified over a wood fire. Read [our story](/about) or [visit the shop](/shop).
MD,
                'faqs' => [
                    ['question' => 'What is A2 ghee?', 'answer' => 'Ghee made from the milk of cows that produce only the A2 type of beta-casein protein, which includes most traditional Indian (desi) breeds.'],
                    ['question' => 'Does ghee contain A1 or A2 protein?', 'answer' => 'Only in traces. Ghee is almost entirely fat, and most milk protein is strained out with the solids when butter is clarified.'],
                    ['question' => 'What matters more than the A2 label?', 'answer' => 'How the ghee is made (bilona or machine), what the cows eat, whether anything has been added, and how fresh it is.'],
                ],
            ],
        ];
    }
}
