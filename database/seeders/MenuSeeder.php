<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menu items
        Menu::truncate();

        // Main Navigation Menu Items
        $mainMenuItems = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'url' => '/',
                'type' => 'page',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'About',
                'slug' => 'about',
                'url' => '/about',
                'type' => 'page',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'url' => '/services',
                'type' => 'page',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Venues',
                'slug' => 'venues',
                'url' => '/venues',
                'type' => 'module',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Gallery',
                'slug' => 'gallery',
                'url' => '/gallery',
                'type' => 'module',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Testimonials',
                'slug' => 'testimonials',
                'url' => '/testimonials',
                'type' => 'module',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'url' => '/blog',
                'type' => 'module',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'url' => '/contact',
                'type' => 'page',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        // Create main menu items
        $createdMenus = [];
        foreach ($mainMenuItems as $item) {
            $createdMenus[$item['slug']] = Menu::create($item);
        }

        // Create sub-menu items for Services
        $servicesSubMenus = [
            [
                'title' => 'Wedding Planning',
                'slug' => 'wedding-planning',
                'url' => '/services/wedding-planning',
                'type' => 'page',
                'parent_id' => $createdMenus['services']->id,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Event Coordination',
                'slug' => 'event-coordination',
                'url' => '/services/event-coordination',
                'type' => 'page',
                'parent_id' => $createdMenus['services']->id,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Venue Booking',
                'slug' => 'venue-booking',
                'url' => '/services/venue-booking',
                'type' => 'page',
                'parent_id' => $createdMenus['services']->id,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Catering Services',
                'slug' => 'catering-services',
                'url' => '/services/catering',
                'type' => 'page',
                'parent_id' => $createdMenus['services']->id,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Photography',
                'slug' => 'photography',
                'url' => '/services/photography',
                'type' => 'page',
                'parent_id' => $createdMenus['services']->id,
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        // Create services sub-menu items
        foreach ($servicesSubMenus as $item) {
            Menu::create($item);
        }

        // Create sub-menu items for Gallery
        $gallerySubMenus = [
            [
                'title' => 'Wedding Gallery',
                'slug' => 'wedding-gallery',
                'url' => '/gallery/weddings',
                'type' => 'category',
                'parent_id' => $createdMenus['gallery']->id,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Event Gallery',
                'slug' => 'event-gallery',
                'url' => '/gallery/events',
                'type' => 'category',
                'parent_id' => $createdMenus['gallery']->id,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Venue Showcase',
                'slug' => 'venue-showcase',
                'url' => '/gallery/venues',
                'type' => 'category',
                'parent_id' => $createdMenus['gallery']->id,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        // Create gallery sub-menu items
        foreach ($gallerySubMenus as $item) {
            Menu::create($item);
        }

        // Additional utility menu items
        $utilityMenus = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'url' => '/privacy',
                'type' => 'page',
                'sort_order' => 100,
                'is_active' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'url' => '/terms',
                'type' => 'page',
                'sort_order' => 101,
                'is_active' => true,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'url' => '/faq',
                'type' => 'page',
                'sort_order' => 102,
                'is_active' => true,
            ],
        ];

        // Create utility menu items
        foreach ($utilityMenus as $item) {
            Menu::create($item);
        }

        $this->command->info('Menu items created successfully!');
        $this->command->info('Created ' . Menu::count() . ' menu items in total.');
    }
}