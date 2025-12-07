<?php
if (!defined('ABSPATH')) {
    exit;
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

    $seed      = max(1, absint($seed));
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
