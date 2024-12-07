export default {
    title: "Laravel AdminLTE",
    description: "Easy AdminLTE integration with Laravel",
    lastUpdated: true,
    base: '/Laravel-AdminLTE',
    themeConfig: {
        logo: '/imgs/AdminLTELogo.ico',
        sidebar: [
            {
                text: 'Overview',
                collapsed: false,
                items: [
                    { text: 'Home', link: '/' },
                    { text: 'Requirements', link: '/sections/overview/requirements' },
                    { text: 'Installation', link: '/sections/overview/installation' },
                    { text: 'Updating', link: '/sections/overview/updating' },
                    { text: 'Usage', link: '/sections/overview/usage' },
                    { text: 'Authentication Views', link: '/sections/overview/authentication_views' },
                    { text: 'Artisan commands', link: '/sections/overview/artisan_console_commands' }
                ]
            }, {
                text: 'Configuration',
                collapsed: false,
                items: [
                    { text: 'Basic configuration', link: '/sections/configuration/basic_configuration' },
                    { text: 'Layout & styling', link: '/sections/configuration/layout_and_styling' },
                    { text: 'Menu ', link: '/sections/configuration/menu' },
                    { text: 'Special menu items', link: '/sections/configuration/special_menu_items' },
                    { text: 'Plugins', link: '/sections/configuration/plugins' },
                    { text: 'Misc config', link: '/sections/configuration/other' },
                    { text: 'Translations', link: '/sections/configuration/translations' },
                    { text: 'Views customization', link: '/sections/configuration/views_customization' },
                    { text: 'IFrame mode', link: '/sections/configuration/iframe_mode' }
                ]
            }, {
                text: 'Components',
                collapsed: false,
                items: [
                    { text: 'Intro & Categories', link: '/sections/components/components_categories' },
                    { text: 'Basic form components', link: '/sections/components/basic_forms_components' },
                    { text: 'Advanced form components', link: '/sections/components/advanced_forms_components' },
                    { text: 'Tool components', link: '/sections/components/tool_components' },
                    { text: 'Widget components', link: '/sections/components/widget_components' },
                    { text: 'Components customization', link: '/sections/components/components_customization' }
                ]
            }, {
                text: 'Contribution & Extras',
                collapsed: true,
                items: [
                    { text: 'Issues & Pull Request', link: '/sections/contribution_and_extras/issues_questions_and_pull_requests' },
                    { text: 'Related Packages', link: '/sections/contribution_and_extras/related_packages' }
                ]
            }
        ],
        socialLinks: [
            { icon: 'github', link: 'https://github.com/jeroennoten/Laravel-AdminLTE' }
        ],
        search: {
            provider: 'local'
        }
    },
    head: [
        [
            'link',
            { rel: 'icon', href: '/Laravel-AdminLTE/imgs/AdminLTELogo.ico' }
        ]
    ]
};
