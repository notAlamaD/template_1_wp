<?php
if (!defined('FIN_ECONOMY_VERSION')) {
    define('FIN_ECONOMY_VERSION', '1.0.0');
}

function fin_economy_setup() {
    load_theme_textdomain('fin-economy', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 72,
        'width'       => 72,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'fin-economy'),
        'footer'  => __('Footer Menu', 'fin-economy'),
    ]);
}
add_action('after_setup_theme', 'fin_economy_setup');

function fin_economy_hex_to_rgba($color, $alpha = 1) {
    $color = sanitize_hex_color($color);
    if (!$color) {
        return 'rgba(0,0,0,' . floatval($alpha) . ')';
    }

    $color = ltrim($color, '#');
    if (strlen($color) === 3) {
        $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
    }

    $rgb = [
        hexdec(substr($color, 0, 2)),
        hexdec(substr($color, 2, 2)),
        hexdec(substr($color, 4, 2)),
    ];

    return sprintf('rgba(%1$d,%2$d,%3$d,%4$.2f)', $rgb[0], $rgb[1], $rgb[2], max(0, min(1, floatval($alpha))));
}

function fin_economy_widgets_init() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'fin-economy'),
        'id'            => 'main_sidebar',
        'description'   => __('Right column for categories and popular posts.', 'fin-economy'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'fin_economy_widgets_init');

function fin_economy_assets() {
    wp_enqueue_style('fin-economy-style', get_stylesheet_uri(), [], FIN_ECONOMY_VERSION);
    wp_enqueue_script('fin-economy-script', get_template_directory_uri() . '/main.js', [], FIN_ECONOMY_VERSION, true);

    $hero_bg_start = get_theme_mod('fin_economy_hero_bg_start', '#0f172a');
    $hero_bg_end   = get_theme_mod('fin_economy_hero_bg_end', '#1e3a8a');
    $hero_text     = get_theme_mod('fin_economy_hero_text_color', '#e2e8f0');
    $header_bg   = get_theme_mod('fin_economy_header_bg_color', '#ffffff');
    $header_text = get_theme_mod('fin_economy_header_text_color', '#0f172a');
    $accent      = get_theme_mod('fin_economy_accent_color', '#2563eb');
    $button_bg   = get_theme_mod('fin_economy_button_bg_color', $accent);
    $button_text = get_theme_mod('fin_economy_button_text_color', '#ffffff');
    $button_hover = get_theme_mod('fin_economy_button_hover_color', '#1d4ed8');
    $footer_bg   = get_theme_mod('fin_economy_footer_bg_color', '#0b1220');
    $footer_text = get_theme_mod('fin_economy_footer_text_color', '#e2e8f0');

    $hero_surface = fin_economy_hex_to_rgba($hero_text, 0.08);
    $hero_border  = fin_economy_hex_to_rgba($hero_text, 0.18);

    $custom_css = sprintf(
        ':root{--hero-bg-start:%1$s;--hero-bg-end:%2$s;--hero-text:%3$s;--hero-surface:%4$s;--hero-border:%5$s;--header-bg:%6$s;--header-text:%7$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);--accent:%8$s;--button-bg:%11$s;--button-text:%12$s;--button-hover:%13$s;--footer-bg:%9$s;--footer-text:%10$s;--footer-border:rgba(255,255,255,0.08);} .site-header{background:%6$s;color:%7$s;} .site-footer{background:%9$s;color:%10$s;}',
        esc_html($hero_bg_start),
        esc_html($hero_bg_end),
        esc_html($hero_text),
        esc_html($hero_surface),
        esc_html($hero_border),
        esc_html($header_bg),
        esc_html($header_text),
        esc_html($accent),
        esc_html($footer_bg),
        esc_html($footer_text),
        esc_html($button_bg),
        esc_html($button_text),
        esc_html($button_hover)
    );

    wp_add_inline_style('fin-economy-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'fin_economy_assets');

function fin_economy_customize_register($wp_customize) {
    $wp_customize->add_section('fin_economy_hero_section', [
        'title'    => __('Hero Block', 'fin-economy'),
        'priority' => 25,
    ]);

    $wp_customize->add_setting('fin_economy_hero_featured_category', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_featured_category', [
        'label'       => __('Featured post category', 'fin-economy'),
        'description' => __('Choose which category feeds the large hero article. Leave empty for the latest post from any category.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'dropdown-categories',
    ]);

    $wp_customize->add_setting('fin_economy_hero_accent_category', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_accent_category', [
        'label'       => __('Quick accents category', 'fin-economy'),
        'description' => __('Select a category for the smaller highlight cards. Falls back to the featured category when empty.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'dropdown-categories',
    ]);

    $wp_customize->add_setting('fin_economy_hero_accent_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_accent_count', [
        'label'       => __('Number of quick accents', 'fin-economy'),
        'description' => __('Control how many smaller cards appear beside the hero (1–6).', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 6,
        ],
    ]);

    $wp_customize->add_setting('fin_economy_hero_bg_start', [
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_start', [
        'label'       => __('Hero background start', 'fin-economy'),
        'description' => __('Left/top gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_bg_end', [
        'default'           => '#1e3a8a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_end', [
        'label'       => __('Hero background end', 'fin-economy'),
        'description' => __('Right/bottom gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_text_color', [
        'default'           => '#e2e8f0',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_text_color', [
        'label'       => __('Hero text color', 'fin-economy'),
        'description' => __('Applies to hero headings, excerpts, and accent cards.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_section('fin_economy_header_section', [
        'title'    => __('Header Styling', 'fin-economy'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('fin_economy_header_bg_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_bg_color', [
        'label'   => __('Header background color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_header_text_color', [
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_text_color', [
        'label'   => __('Header text color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_accent_color', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_accent_color', [
        'label'   => __('Accent color', 'fin-economy'),
        'section' => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_bg_color', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_bg_color', [
        'label'       => __('Button background color', 'fin-economy'),
        'description' => __('Applies to primary buttons and hero CTAs.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_hover_color', [
        'default'           => '#1d4ed8',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_hover_color', [
        'label'       => __('Button hover color', 'fin-economy'),
        'description' => __('Used for hover and focus states.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_text_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_text_color', [
        'label'       => __('Button text color', 'fin-economy'),
        'description' => __('Adjust label contrast for buttons sitewide.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_section('fin_economy_footer_section', [
        'title'    => __('Footer', 'fin-economy'),
        'priority' => 40,
    ]);

    $wp_customize->add_setting('fin_economy_footer_text', [
        'default'           => __('© 2025 «Фінанси та економія».', 'fin-economy'),
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('fin_economy_footer_text', [
        'label'   => __('Footer text', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('fin_economy_footer_bg_color', [
        'default'           => '#0b1220',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_footer_bg_color', [
        'label'   => __('Footer background color', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
    ]));

    $wp_customize->add_setting('fin_economy_footer_text_color', [
        'default'           => '#e2e8f0',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_footer_text_color', [
        'label'   => __('Footer text color', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
    ]));

    $social_networks = [
        'facebook'  => __('Facebook URL', 'fin-economy'),
        'twitter'   => __('X / Twitter URL', 'fin-economy'),
        'instagram' => __('Instagram URL', 'fin-economy'),
        'linkedin'  => __('LinkedIn URL', 'fin-economy'),
        'youtube'   => __('YouTube URL', 'fin-economy'),
    ];

    foreach ($social_networks as $key => $label) {
        $setting_id = 'fin_economy_social_' . $key;
        $wp_customize->add_setting($setting_id, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $label,
            'section' => 'fin_economy_footer_section',
            'type'    => 'url',
        ]);
    }

    $wp_customize->add_setting('fin_economy_social_rss', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('fin_economy_social_rss', [
        'label'       => __('RSS feed URL', 'fin-economy'),
        'description' => __('Leave empty to hide the RSS icon.', 'fin-economy'),
        'section'     => 'fin_economy_footer_section',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('fin_economy_head_scripts', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('fin_economy_head_scripts', [
        'label'       => __('Custom scripts in <head>', 'fin-economy'),
        'description' => __('Paste analytics or verification snippets. Avoid <script> tags with remote sources when possible.', 'fin-economy'),
        'section'     => 'title_tagline',
        'type'        => 'textarea',
    ]);
}
add_action('customize_register', 'fin_economy_customize_register');

function fin_economy_head_scripts() {
    $scripts = get_theme_mod('fin_economy_head_scripts', '');
    if (!empty($scripts)) {
        echo '<!-- Custom head scripts -->' . wp_kses_post($scripts);
    }
}
add_action('wp_head', 'fin_economy_head_scripts');

function fin_economy_get_popular_posts($count = 4) {
    $query = new WP_Query([
        'posts_per_page'      => $count,
        'orderby'             => 'comment_count',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    ]);

    if (!$query->have_posts()) {
        $query = new WP_Query([
            'posts_per_page'      => $count,
            'ignore_sticky_posts' => true,
        ]);
    }

    return $query;
}

function fin_economy_register_demo_page() {
    add_theme_page(
        __('Demo Content', 'fin-economy'),
        __('Demo Content', 'fin-economy'),
        'edit_theme_options',
        'fin-economy-demo',
        'fin_economy_render_demo_page'
    );
}
add_action('admin_menu', 'fin_economy_register_demo_page');

function fin_economy_render_demo_page() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    $status = isset($_GET['demo_status']) ? sanitize_text_field(wp_unslash($_GET['demo_status'])) : '';
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Demo Content', 'fin-economy'); ?></h1>
        <p><?php esc_html_e('Generate sample posts, pages, categories, and menus to preview the theme.', 'fin-economy'); ?></p>

        <?php if ($status === 'success') : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('Demo content created. You can now assign menus and preview the homepage.', 'fin-economy'); ?></p>
            </div>
        <?php elseif ($status === 'error') : ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('Demo content could not be created. Please try again.', 'fin-economy'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('fin_economy_demo_import'); ?>
            <input type="hidden" name="action" value="fin_economy_import_demo" />
            <?php submit_button(__('Create demo content', 'fin-economy')); ?>
        </form>
    </div>
    <?php
}

function fin_economy_handle_demo_import() {
    if (!current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You do not have permission to import demo content.', 'fin-economy'));
    }

    check_admin_referer('fin_economy_demo_import');

    $result = fin_economy_create_demo_content();
    $status = $result ? 'success' : 'error';

    wp_safe_redirect(add_query_arg('demo_status', $status, admin_url('themes.php?page=fin-economy-demo')));
    exit;
}
add_action('admin_post_fin_economy_import_demo', 'fin_economy_handle_demo_import');

function fin_economy_import_demo_image($post_id, $seed = 1) {
    if (has_post_thumbnail($post_id)) {
        return;
    }

    $seed     = max(1, absint($seed));
    $image_url = esc_url_raw(sprintf('https://picsum.photos/seed/fin-economy-%1$d/1200/675', $seed));

    if (!function_exists('media_sideload_image')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $attachment_id = media_sideload_image($image_url, $post_id, null, 'id');

    if (!is_wp_error($attachment_id)) {
        set_post_thumbnail($post_id, (int) $attachment_id);
    }
}

function fin_economy_create_demo_content() {
    $categories = [
        'kommunalka' => __('Комуналка', 'fin-economy'),
        'tariff'     => __('Тарифи', 'fin-economy'),
        'economy'    => __('Економія', 'fin-economy'),
        'finance'    => __('Фінанси', 'fin-economy'),
        'invest'     => __('Інвестиції', 'fin-economy'),
    ];

    $category_ids = [];
    foreach ($categories as $slug => $label) {
        $existing = get_category_by_slug($slug);
        if ($existing) {
            $category_ids[$slug] = $existing->term_id;
            continue;
        }

        $inserted = wp_insert_term($label, 'category', ['slug' => $slug]);
        if (is_wp_error($inserted)) {
            continue;
        }
        $category_ids[$slug] = $inserted['term_id'];
    }

    $pages = [
        __('Політика', 'fin-economy'),
        __('Про сайт', 'fin-economy'),
        __('Реклама', 'fin-economy'),
    ];

    foreach ($pages as $title) {
        if (!get_page_by_title($title)) {
            wp_insert_post([
                'post_title'   => $title,
                'post_content' => __('Статична сторінка-заглушка для демонстрації теми.', 'fin-economy'),
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
        }
    }

    $posts = [
        [
            'title'   => __('Як знизити рахунки за комунальні послуги цієї зими', 'fin-economy'),
            'content' => __('Підготували огляд простих кроків, які реально допомагають платити менше: від налаштування бойлера до вибору енергоощадних ламп.', 'fin-economy') . "\n\n" . __('Розповідаємо, як слідкувати за нічними тарифами, що поставити на таймер, а також як перевірити, чи не витрачає ваша техніка більше, ніж заявлено.', 'fin-economy') . "\n\n" . __('Окремо пояснюємо, які компенсації діють для родин та як подати заявку онлайн без черг.', 'fin-economy'),
            'cat'     => 'kommunalka',
            'image'   => 1,
        ],
        [
            'title'   => __('Нове коригування тарифів: що зміниться з наступного місяця', 'fin-economy'),
            'content' => __('Коротко про рішення регулятора: які послуги подорожчають, де тарифи лишаться без змін і як це вплине на середню квитанцію.', 'fin-economy') . "\n\n" . __('Ми зібрали коментарі експертів та підготували таблицю з ключовими цифрами, щоб ви могли спланувати бюджет заздалегідь.', 'fin-economy') . "\n\n" . __('Також нагадуємо про програми субсидій та пільг, що діють у вашому регіоні.', 'fin-economy'),
            'cat'     => 'tariff',
            'image'   => 2,
        ],
        [
            'title'   => __('5 звичок, які допоможуть заощадити щодня', 'fin-economy'),
            'content' => __('Зміни у побуті не обовʼязково складні: сортуємо покупки за пріоритетами, плануємо харчування на тиждень та користуємося кешбеком обережно.', 'fin-economy') . "\n\n" . __('Ділимося прикладами, як відмовитися від імпульсивних витрат і при цьому не втратити в комфорті.', 'fin-economy') . "\n\n" . __('Додаємо чекліст для завантаження, щоб впровадити ці звички поступово.', 'fin-economy'),
            'cat'     => 'economy',
            'image'   => 3,
        ],
        [
            'title'   => __('Огляд банківських депозитів: ставки та бонуси', 'fin-economy'),
            'content' => __('Порівняли пропозиції найбільших банків: де є бонус за перше розміщення, а де — підвищена ставка на короткий термін.', 'fin-economy') . "\n\n" . __('Звертаємо увагу на умови дострокового розірвання, ліміти гарантування та додаткові комісії.', 'fin-economy') . "\n\n" . __('Розповідаємо, як перевірити надійність банку і що робити зі старими депозитними договорами.', 'fin-economy'),
            'cat'     => 'finance',
            'image'   => 4,
        ],
        [
            'title'   => __('Куди інвестують українці у 2025 році', 'fin-economy'),
            'content' => __('Порівнюємо популярні інструменти: державні облігації, ETF та ринок оренди нерухомості.', 'fin-economy') . "\n\n" . __('Розглядаємо приклади портфелів для різних рівнів ризику й пояснюємо, як диверсифікація зменшує втрати.', 'fin-economy') . "\n\n" . __('Є окремий блок про податки та що зміниться після 1 липня.', 'fin-economy'),
            'cat'     => 'invest',
            'image'   => 5,
        ],
        [
            'title'   => __('Як працює індекс споживчих цін і чому він важливий', 'fin-economy'),
            'content' => __('Пояснюємо, як статистики рахують CPI, які кошики беруть за основу та як часто оновлюють методологію.', 'fin-economy') . "\n\n" . __('Наводимо приклади впливу інфляції на кредити, заощадження та зарплати.', 'fin-economy') . "\n\n" . __('Додаємо графік динаміки за останні роки та підказуємо, де стежити за офіційними даними.', 'fin-economy'),
            'cat'     => 'finance',
            'image'   => 6,
        ],
    ];

    foreach ($posts as $index => $post) {
        if (get_page_by_title($post['title'], OBJECT, 'post')) {
            continue;
        }

        $cat_id = isset($category_ids[$post['cat']]) ? (int) $category_ids[$post['cat']] : 0;

        $post_id = wp_insert_post([
            'post_title'    => $post['title'],
            'post_content'  => $post['content'],
            'post_excerpt'  => $post['content'],
            'post_status'   => 'publish',
            'post_type'     => 'post',
            'post_category' => $cat_id ? [$cat_id] : [],
        ]);

        if (!is_wp_error($post_id)) {
            fin_economy_import_demo_image($post_id, isset($post['image']) ? (int) $post['image'] : $index);
        }
    }

    $primary_menu = wp_get_nav_menu_object(__('Головне меню', 'fin-economy'));
    if (!$primary_menu) {
        $menu_id = wp_create_nav_menu(__('Головне меню', 'fin-economy'));
        foreach ($categories as $slug => $label) {
            if (isset($category_ids[$slug])) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'  => $label,
                    'menu-item-url'    => get_category_link($category_ids[$slug]),
                    'menu-item-status' => 'publish',
                ]);
            }
        }

        $locations = get_theme_mod('nav_menu_locations');
        if (!is_array($locations)) {
            $locations = [];
        }

        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    $footer_menu = wp_get_nav_menu_object(__('Меню футера', 'fin-economy'));
    if (!$footer_menu) {
        $footer_id = wp_create_nav_menu(__('Меню футера', 'fin-economy'));
        foreach ($pages as $title) {
            $page = get_page_by_title($title);
            if ($page) {
                wp_update_nav_menu_item($footer_id, 0, [
                    'menu-item-title'  => $page->post_title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'   => 'post_type',
                    'menu-item-status' => 'publish',
                ]);
            }
        }

        $locations = get_theme_mod('nav_menu_locations');
        if (!is_array($locations)) {
            $locations = [];
        }

        $locations['footer'] = $footer_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    return true;
}
