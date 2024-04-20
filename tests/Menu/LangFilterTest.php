<?php

class LangFilterTest extends TestCase
{
    public function testEnglishTranslations()
    {
        // Add translation lines and setup the locale.

        $lines = [
            'menu.header_x' => 'Header X',
            'menu.home' => 'Home',
            'menu.home_label' => 'Home Label',
            'menu.about' => 'About',
        ];

        $this->app->make('translator')->addLines($lines, 'en', 'adminlte');
        $this->app->setLocale('en');

        // Setup menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['header' => 'header_x']);
        $builder->add(['text' => 'home', 'url' => 'home', 'label' => 'home_label']);
        $builder->add(['text' => 'about', 'url' => 'about']);
        $builder->add(['header' => 'header_y']);

        // Check translations.

        $this->assertEquals('Header X', $builder->menu[0]['header']);
        $this->assertEquals('Home', $builder->menu[1]['text']);
        $this->assertEquals('Home Label', $builder->menu[1]['label']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('header_y', $builder->menu[3]['header']);
    }

    public function testSpanishTranslations()
    {
        // Add translation lines and setup the locale.

        $lines = [
            'menu.header_x' => 'Encabezado X',
            'menu.home' => 'Inicio',
            'menu.home_label' => 'Etiqueta de inicio',
            'menu.about' => 'Acerca de',
        ];

        $this->app->make('translator')->addLines($lines, 'es', 'adminlte');
        $this->app->setLocale('es');

        // Setup menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['header' => 'header_x']);
        $builder->add(['text' => 'home', 'url' => 'home', 'label' => 'home_label']);
        $builder->add(['text' => 'about', 'url' => 'about']);
        $builder->add(['header' => 'header_y']);

        // Check translations.

        $this->assertEquals('Encabezado X', $builder->menu[0]['header']);
        $this->assertEquals('Inicio', $builder->menu[1]['text']);
        $this->assertEquals('Etiqueta de inicio', $builder->menu[1]['label']);
        $this->assertEquals('Acerca de', $builder->menu[2]['text']);
        $this->assertEquals('header_y', $builder->menu[3]['header']);
    }

    public function testTranslationsWithoutAdminlteNamespace()
    {
        // Add translation lines and setup the locale.

        $lines = [
            'menu.header_x' => 'Header X',
            'menu.home' => 'Home',
            'menu.home_label' => 'Home Label',
            'menu.about' => 'About',
        ];

        $this->app->make('translator')->addLines($lines, 'en');
        $this->app->setLocale('en');

        // Setup menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['header' => 'header_x']);
        $builder->add(['text' => 'home', 'url' => 'home', 'label' => 'home_label']);
        $builder->add(['text' => 'about', 'url' => 'about']);
        $builder->add(['header' => 'header_y']);

        // Check translations.

        $this->assertEquals('Header X', $builder->menu[0]['header']);
        $this->assertEquals('Home', $builder->menu[1]['text']);
        $this->assertEquals('Home Label', $builder->menu[1]['label']);
        $this->assertEquals('About', $builder->menu[2]['text']);
        $this->assertEquals('header_y', $builder->menu[3]['header']);
    }

    public function testTranslationsWithoutNamespaceHasPriority()
    {
        // Add translation lines and setup the locale.

        $lines = ['menu.home' => 'Home A'];
        $this->app->make('translator')->addLines($lines, 'en', 'adminlte');

        $lines = ['menu.home' => 'Home B'];
        $this->app->make('translator')->addLines($lines, 'en');

        $this->app->setLocale('en');

        // Setup menu.

        $builder = $this->makeMenuBuilder();
        $builder->add(['text' => 'home', 'url' => 'home']);

        // Check translations.

        $this->assertEquals('Home B', $builder->menu[0]['text']);
    }

    public function testTranslationWithParams()
    {
        // Add translation lines and setup the locale.

        $lines = [
            'menu.header_with_params' => 'MENU :cat / :subcat',
            'menu.profile_with_params' => 'Perfil de :name',
            'menu.label_with_params' => 'Etiqueta :type',
        ];

        $this->app->make('translator')->addLines($lines, 'es', 'adminlte');
        $this->app->setLocale('es');

        // Setup menu.

        $builder = $this->makeMenuBuilder();

        $builder->add(
            [
                'header' => [
                    'header_with_params',
                    ['cat' => 'CAT', 'subcat' => 'SUBCAT'],
                ],
            ],
            [
                'text' => ['profile_with_params', ['name' => 'Diego']],
                'url' => 'profile',
                'label' => ['label_with_params', ['type' => 'Tipo']],
            ],
            [
                // Test case with partial parameters.
                'header' => ['header_with_params', ['subcat' => 'SUBCAT']],
            ],
            [
                // Test case with empty parameters.
                'header' => ['header_with_params'],
            ],
            [
                // Test case with non-array parameters.
                'header' => ['header_with_params', 'non-array-value'],
            ]
        );

        // Check translations.

        $this->assertEquals('MENU CAT / SUBCAT', $builder->menu[0]['header']);
        $this->assertEquals('Perfil de Diego', $builder->menu[1]['text']);
        $this->assertEquals('Etiqueta Tipo', $builder->menu[1]['label']);
        $this->assertEquals('MENU :cat / SUBCAT', $builder->menu[2]['header']);
        $this->assertEquals('MENU :cat / :subcat', $builder->menu[3]['header']);
        $this->assertEquals('MENU :cat / :subcat', $builder->menu[4]['header']);
    }
}
