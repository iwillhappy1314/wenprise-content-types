<?php

namespace WenpriseContentTypes;

class Taxonomy
{

    /**
     * 快速添加分类方法
     **
     *
     * @param string|array       $tax_slug     分类法名称
     * @param string|array $post_type    关联到的文章类型的名称
     * @param boolean      $is_public    是否公开
     * @param string       $tax_name     分类法菜单名称
     * @param boolean      $hierarchical 是否允许有父级分类
     *
     *
     * @usage   wprs_tax( "work_type", 'work', __("Work Type", 'wprs'), true );
     */
    public static function register(string|array $tax_slug, $post_type, string $tax_name, bool $is_public, bool $hierarchical = true)
    {

        Helpers::loadTextDomain();

        $singular_name = Vendor\Doctrine\Common\Inflector\Inflector::singularize($tax_name);

        //分类法的标签
        $labels = [
            'name'                       => ucwords($tax_name),
            'singular_name'              => ucwords($singular_name),
            'menu_name'                  => ucwords($tax_name),
            'all_items'                  => sprintf(__('All %s', 'wprs'), $tax_name),
            'edit_item'                  => sprintf(__('Edit %s', 'wprs'), $singular_name),
            'view_item'                  => sprintf(__('View %s', 'wprs'), $singular_name),
            'update_item'                => sprintf(__('Upgrade %s', 'wprs'), $singular_name),
            'add_new_item'               => sprintf(__('Add New %s', 'wprs'), $singular_name),
            'new_item_name'              => sprintf(__('New %s', 'wprs'), $singular_name),
            'parent_item'                => sprintf(__('Parent %s', 'wprs'), $singular_name),
            'parent_item_colon'          => sprintf(__('Parent %s', 'wprs'), $singular_name),
            'search_items'               => sprintf(__('Search %s', 'wprs'), $tax_name),
            'popular_items'              => sprintf(__('Popular %s', 'wprs'), $tax_name),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'wprs'), $tax_name),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'wprs'), $tax_name),
            'choose_from_most_used'      => sprintf(__('Choose from the most used %s', 'wprs'), $singular_name),
            'not_found'                  => sprintf(__('No %s found.', 'wprs'), $singular_name),
        ];

        if(is_array($tax_slug)){
            $tax_nicename = $tax_slug[0];
            $tax_rewrite = $tax_slug[1];
        }else{
            $tax_nicename = $tax_slug;
            $tax_rewrite = $tax_slug;
        }

        $labels = apply_filters('wprs_tax_labels_' . $tax_nicename, $labels);

        //分类法参数
        $args = [
            'labels'            => $labels,
            'public'            => $is_public,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => $is_public,
            'show_in_rest'      => $is_public,
            'show_admin_column' => true,
            'hierarchical'      => $hierarchical,
            'rewrite'           => ['slug' => $tax_rewrite],
            'sort'              => true,
        ];

        if ( ! is_array($post_type)) {
            $post_type = [$post_type];
        }

        $args      = apply_filters('wprs_tax_args_' . $tax_nicename, $args);
        $post_type = apply_filters('wprs_tax_types_' . $tax_nicename, $post_type);

        if (strlen($tax_nicename) > 0) {
            foreach ($post_type as $type) {
                register_taxonomy($tax_nicename, $type, $args);
            }
        }
    }

}
