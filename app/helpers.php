<?php

if (! function_exists('hillnest_logo')) {
    function hillnest_logo(): string
    {
        $names = ['logo.png', 'logo.jpg', 'logo.jpeg', 'logo.webp', 'logo.svg'];

        foreach ($names as $name) {
            $public = public_path('images/' . $name);
            if (file_exists($public) && filesize($public) > 100) {
                return asset('images/' . $name);
            }

            $root = base_path('images/' . $name);
            if (file_exists($root) && filesize($root) > 100) {
                if (! is_dir(public_path('images'))) {
                    mkdir(public_path('images'), 0755, true);
                }
                @copy($root, $public);

                return asset('images/' . $name);
            }
        }

        return asset('images/logo.svg');
    }
}

if (! function_exists('hillnest_hero_slides')) {
    function hillnest_hero_slides(): array
    {
        return [
            [
                'image' => 'https://images.unsplash.com/photo-1628088062856-eee32a9352e2?w=1920&q=85&auto=format&fit=crop',
                'title' => 'Pure Bilona Cow Ghee',
                'subtitle' => 'Hand-churned in upper Shimla — rich, golden, authentic.',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1589985278721-4afeaa8bce84?w=1920&q=85&auto=format&fit=crop',
                'title' => 'From Himalayan Meadows',
                'subtitle' => 'Free-grazing cows. Traditional wooden bilona. No shortcuts.',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1615485926563-27382494f159?w=1920&q=85&auto=format&fit=crop',
                'title' => 'Food That Heals, Not Hype',
                'subtitle' => 'Pure · Traditional · Ethically crafted in Himachal Pradesh.',
            ],
        ];
    }
}
