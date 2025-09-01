<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Get all active menus in hierarchical structure
     */
    public function index(): JsonResponse
    {
        $menus = Menu::active()
            ->rootLevel()
            ->with(['activeChildren.activeChildren']) // Support 2 levels deep
            ->ordered()
            ->get();

        $menuData = $this->formatMenuItems($menus);

        return response()->json([
            'success' => true,
            'data' => $menuData,
            'meta' => [
                'total' => $menus->count(),
            ]
        ]);
    }

    /**
     * Get primary navigation menu
     */
    public function primary(): JsonResponse
    {
        // Get the main navigation items (you can customize this based on your needs)
        $primaryMenu = [
            [
                'id' => 'home',
                'title' => 'Home',
                'url' => '/',
                'slug' => 'home',
                'type' => 'page',
                'target' => '_self',
                'sort_order' => 1,
                'children' => []
            ],
            [
                'id' => 'about',
                'title' => 'About',
                'url' => '/about',
                'slug' => 'about',
                'type' => 'page',
                'target' => '_self',
                'sort_order' => 2,
                'children' => []
            ],
            [
                'id' => 'venues',
                'title' => 'Venues',
                'url' => '/venues',
                'slug' => 'venues',
                'type' => 'module',
                'target' => '_self',
                'sort_order' => 3,
                'children' => []
            ],
            [
                'id' => 'galleries',
                'title' => 'Gallery',
                'url' => '/gallery',
                'slug' => 'gallery',
                'type' => 'module',
                'target' => '_self',
                'sort_order' => 4,
                'children' => []
            ],
            [
                'id' => 'testimonials',
                'title' => 'Testimonials',
                'url' => '/testimonials',
                'slug' => 'testimonials',
                'type' => 'module',
                'target' => '_self',
                'sort_order' => 5,
                'children' => []
            ],
            [
                'id' => 'blog',
                'title' => 'Blog',
                'url' => '/blog',
                'slug' => 'blog',
                'type' => 'module',
                'target' => '_self',
                'sort_order' => 6,
                'children' => []
            ],
            [
                'id' => 'contact',
                'title' => 'Contact',
                'url' => '/contact',
                'slug' => 'contact',
                'type' => 'page',
                'target' => '_self',
                'sort_order' => 7,
                'children' => []
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $primaryMenu,
            'meta' => [
                'total' => count($primaryMenu),
                'type' => 'primary_navigation'
            ]
        ]);
    }

    /**
     * Get footer menu
     */
    public function footer(): JsonResponse
    {
        $footerMenu = [
            [
                'section' => 'Quick Links',
                'items' => [
                    ['title' => 'Home', 'url' => '/', 'target' => '_self'],
                    ['title' => 'About', 'url' => '/about', 'target' => '_self'],
                    ['title' => 'Venues', 'url' => '/venues', 'target' => '_self'],
                    ['title' => 'Gallery', 'url' => '/gallery', 'target' => '_self'],
                ]
            ],
            [
                'section' => 'Services',
                'items' => [
                    ['title' => 'Wedding Planning', 'url' => '/services/wedding-planning', 'target' => '_self'],
                    ['title' => 'Event Coordination', 'url' => '/services/event-coordination', 'target' => '_self'],
                    ['title' => 'Venue Booking', 'url' => '/services/venue-booking', 'target' => '_self'],
                    ['title' => 'Catering', 'url' => '/services/catering', 'target' => '_self'],
                ]
            ],
            [
                'section' => 'Information',
                'items' => [
                    ['title' => 'Blog', 'url' => '/blog', 'target' => '_self'],
                    ['title' => 'Testimonials', 'url' => '/testimonials', 'target' => '_self'],
                    ['title' => 'Contact', 'url' => '/contact', 'target' => '_self'],
                    ['title' => 'Privacy Policy', 'url' => '/privacy', 'target' => '_self'],
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $footerMenu,
            'meta' => [
                'total' => count($footerMenu),
                'type' => 'footer_navigation'
            ]
        ]);
    }

    /**
     * Get admin navigation structure
     */
    public function admin(): JsonResponse
    {
        $adminMenu = [
            [
                'section' => 'Overview',
                'items' => [
                    [
                        'title' => 'Dashboard',
                        'url' => '/admin',
                        'icon' => 'dashboard',
                        'route' => 'admin.dashboard',
                        'permission' => 'admin.access'
                    ]
                ]
            ],
            [
                'section' => 'System',
                'items' => [
                    [
                        'title' => 'Users',
                        'url' => '/admin/users',
                        'icon' => 'users',
                        'route' => 'admin.users.index',
                        'permission' => 'users.manage'
                    ],
                    [
                        'title' => 'Media Library',
                        'url' => '/admin/media',
                        'icon' => 'photo',
                        'route' => 'admin.media.index',
                        'permission' => 'media.manage'
                    ]
                ]
            ],
            [
                'section' => 'Content',
                'items' => [
                    [
                        'title' => 'About Slides',
                        'url' => '/admin/about-slides',
                        'icon' => 'presentation',
                        'route' => 'admin.about-slides.index',
                        'permission' => 'content.manage'
                    ],
                    [
                        'title' => 'Testimonials',
                        'url' => '/admin/testimonials',
                        'icon' => 'chat',
                        'route' => 'admin.testimonials.index',
                        'permission' => 'content.manage'
                    ],
                    [
                        'title' => 'Galleries',
                        'url' => '/admin/galleries',
                        'icon' => 'photos',
                        'route' => 'admin.galleries.index',
                        'permission' => 'content.manage'
                    ],
                    [
                        'title' => 'Venues',
                        'url' => '/admin/venues',
                        'icon' => 'building',
                        'route' => 'admin.venues.index',
                        'permission' => 'content.manage'
                    ],
                    [
                        'title' => 'Blogs',
                        'url' => '/admin/blogs',
                        'icon' => 'pencil',
                        'route' => 'admin.blogs.index',
                        'permission' => 'content.manage'
                    ],
                    [
                        'title' => 'Contact Submissions',
                        'url' => '/admin/contact-submissions',
                        'icon' => 'mail',
                        'route' => 'admin.contact-submissions.index',
                        'permission' => 'content.view'
                    ]
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $adminMenu,
            'meta' => [
                'total' => collect($adminMenu)->sum(fn($section) => count($section['items'])),
                'type' => 'admin_navigation'
            ]
        ]);
    }

    /**
     * Format menu items recursively
     */
    private function formatMenuItems($menus): array
    {
        return $menus->map(function ($menu) {
            return [
                'id' => $menu->id,
                'title' => $menu->title,
                'slug' => $menu->slug,
                'url' => $menu->full_url,
                'target' => $menu->target,
                'type' => $menu->type,
                'sort_order' => $menu->sort_order,
                'meta' => $menu->meta,
                'has_children' => $menu->hasActiveChildren(),
                'children' => $menu->activeChildren ? $this->formatMenuItems($menu->activeChildren) : [],
            ];
        })->toArray();
    }
}