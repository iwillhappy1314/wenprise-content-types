<?php

namespace WenpriseContentTypes;

class ContentType
{

    /**
     * 快速添加文章类型
     **
     *
     * @param string        $slug         文章类型名称
     * @param string        $name         文章类型菜单名称
     * @param array|boolean $support      文章类型支持的功能
     * @param boolean       $is_publish   文章类型是否在前后台可见
     * @param boolean       $hierarchical 文章是否分级显示
     * @param string        $icon         后台使用的 dashicon 图标
     *
     * @usage   wprs_types( "work", __("Works", 'wprs'), [ 'title', 'editor', 'comments', 'thumbnail', 'author' ], true, false, 'dashicons-art' );
     */
    public static function register(string $slug, string $name, $support, bool $is_publish, bool $hierarchical = false, string $icon = 'dashicons-networking')
    {
        Helpers::loadTextDomain();

        $singular_name = Vendor\Doctrine\Common\Inflector\Inflector::singularize($name);

        //文章类型的标签
        $labels = [
            'name'               => ucwords($name),
            'singular_name'      => ucwords($singular_name),
            'add_new'            => sprintf(__('Add New %s', 'wprs'), $singular_name),
            'add_new_item'       => sprintf(__('Add New %s', 'wprs'), $singular_name),
            'edit_item'          => sprintf(__('Edit %s', 'wprs'), $singular_name),
            'new_item'           => sprintf(__('New %s', 'wprs'), $singular_name),
            'all_items'          => sprintf(__('All %s', 'wprs'), $name),
            'view_item'          => sprintf(__('View %s', 'wprs'), $singular_name),
            'search_items'       => sprintf(__('Search %s', 'wprs'), $name),
            'not_found'          => sprintf(__('Could not find %s', 'wprs'), $singular_name),
            'not_found_in_trash' => sprintf(__('Could not find %s in trash', 'wprs'), $singular_name),
            'menu_name'          => ucfirst($name),
        ];

        $labels = apply_filters('wprs_type_labels_' . $slug, $labels);

        //注册文章类型需要的参数
        $args = [
            'labels'              => $labels,
            'description'         => '',
            'public'              => $is_publish,
            'exclude_from_search' => ! $is_publish,
            'publicly_queryable'  => $is_publish,
            'show_ui'             => true,
            'show_in_nav_menus'   => $is_publish,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => $is_publish,
            'menu_position'       => 5,
            'menu_icon'           => $icon,
            'hierarchical'        => $hierarchical,
            'supports'            => $support,
            'has_archive'         => $is_publish,
            'rewrite'             => ['slug' => $slug],
            'query_var'           => $is_publish,
        ];


        $args = apply_filters('wprs_type_args_' . $slug, $args);

        if (strlen($slug) > 0) {
            register_post_type($slug, $args);
        }
    }


    /**
     * 添加文章类型权限到角色
     *
     * @param string $post_type
     * @param string $role_name
     *
     * @return \WP_Role|null
     */
    public static function add_caps(string $post_type, string $role_name = 'administrator'): ?\WP_Role
    {
        $role = get_role($role_name);

        $plural = Vendor\Doctrine\Common\Inflector\Inflector::singularize($post_type);

        $caps = [
            'read_' . $plural,
            'read_private_' . $plural,
            'edit_' . $plural,
            'edit_others_' . $plural,
            'edit_published_' . $plural,
            'edit_private_' . $plural,
            'delete_' . $plural,
            'delete_others_' . $plural,
            'delete_published_' . $plural,
            'delete_private_' . $plural,
            'publish_' . $plural,
            'moderate_comments_' . $plural,
        ];

        foreach ($caps as $cap) {
            $role->add_cap($cap);
        }

        return $role;
    }


    /**
     * 从角色移除文章类型权限
     *
     * @param string $post_type
     * @param string $role_name
     *
     * @return \WP_Role|null
     */
    public static function remove_caps(string $post_type, string $role_name = 'administrator'): ?\WP_Role
    {
        $role = get_role($role_name);

        $singular_name = Inflector::pluralize($post_type);

        $caps = [
            'read_' . $plural,
            'read_private_' . $plural,
            'edit_' . $plural,
            'edit_others_' . $plural,
            'edit_published_' . $plural,
            'edit_private_' . $plural,
            'delete_' . $plural,
            'delete_others_' . $plural,
            'delete_published_' . $plural,
            'delete_private_' . $plural,
            'publish_' . $plural,
            'moderate_comments_' . $plural,
        ];

        foreach ($caps as $cap) {
            $role->remove_cap($cap);
        }

        return $role;
    }

}




