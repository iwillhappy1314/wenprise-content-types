<?php

/**
 * 快速添加文章类型
 **
 *
 * @param string  $slug         文章类型名称
 * @param string  $name         文章类型菜单名称
 * @param array   $support      文章类型支持的功能
 * @param boolean $is_publish   文章类型是否在前后台可见
 * @param boolean $hierarchical 文章是否分级显示
 * @param string  $icon         后台使用的 dashicon 图标
 *                              *
 *
 * @usage   wprs_types( "work", "Works", [ 'title', 'editor', 'comments', 'thumbnail', 'author' ], true, false, 'dashicons-art' );
 */
function wprs_types( $slug, $name, $support, $is_publish, $hierarchical = false, $icon = 'dashicons-networking' )
{

	//文章类型的标签
	$labels = [
		'name'               => $name,
		'singular_name'      => $name,
		'add_new'            => sprintf( __( 'Add New %s', 'wprs' ), $name ),
		'add_new_item'       => sprintf( __( 'Add New %s', 'wprs' ), $name ),
		'edit_item'          => sprintf( __( 'Edit %s', 'wprs' ), $name ),
		'new_item'           => sprintf( __( 'New %s', 'wprs' ), $name ),
		'all_items'          => sprintf( __( 'All %s', 'wprs' ), $name ),
		'view_item'          => sprintf( __( 'View %s', 'wprs' ), $name ),
		'search_items'       => sprintf( __( 'Search %s', 'wprs' ), $name ),
		'not_found'          => sprintf( __( 'Could not find %s', 'wprs' ), $name ),
		'not_found_in_trash' => sprintf( __( 'Could not find %s in trash', 'wprs' ), $name ),
		'menu_name'          => $name,
	];

	$labels = apply_filters( 'wprs_type_labels_' . $slug, $labels );

	//注册文章类型需要的参数
	//注册文章类型需要的参数
	$args = [
		'labels'              => $labels,
		'description'         => '',
		'public'              => $is_publish,
		'exclude_from_search' => ! $is_publish,
		'publicly_queryable'  => $is_publish,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => $icon,
		'hierarchical'        => $hierarchical,
		'supports'            => $support,
		'has_archive'         => $is_publish,
		'rewrite'             => [ 'slug' => $slug ],
		'query_var'           => $is_publish,
		'map_meta_cap'        => true,
		'capabilities'        => [
			'read_post'              => 'read_' . $slug,
			'read_private_posts'     => 'read_private_' . $slug,
			'edit_post'              => 'edit_' . $slug,
			'edit_posts'             => 'edit_' . $slug . 's',
			'edit_others_posts'      => 'edit_others_' . $slug,
			'edit_published_posts'   => 'edit_published_' . $slug,
			'edit_private_posts'     => 'edit_private_' . $slug,
			'delete_post'            => 'delete_' . $slug,
			'delete_posts'           => 'delete_' . $slug . 's',
			'delete_others_posts'    => 'delete_others_' . $slug,
			'delete_published_posts' => 'delete_published_' . $slug,
			'delete_private_posts'   => 'delete_private_' . $slug,
			'publish_posts'          => 'publish_' . $slug,
			'moderate_comments'      => 'moderate_comments_' . $slug,
		],
	];


	$args = apply_filters( 'wprs_type_args_' . $slug, $args );

	if ( strlen( $slug ) > 0 ) {
		register_post_type( $slug, $args );
	}
}


/**
 * 添加权限到角色
 *
 * @param        $post_type
 * @param string $role_name
 *
 * @return mixed
 */
function wprs_add_caps( $post_type, $role_name = 'administrator' )
{
	$role = get_role( $role_name );

	if ( is_array( $post_type ) ) {
		$singular = $post_type[ 0 ];
		$plural   = $post_type[ 1 ];
	} else {
		$singular = $post_type;
		$plural   = $post_type . 's';
	}

	$role->add_cap( 'read_' . $singular );
	$role->add_cap( 'read_private_' . $singular );
	$role->add_cap( 'edit_' . $singular );
	$role->add_cap( 'edit_' . $plural );
	$role->add_cap( 'edit_others_' . $singular );
	$role->add_cap( 'edit_published_' . $singular );
	$role->add_cap( 'edit_private_' . $singular );
	$role->add_cap( 'delete_' . $singular );
	$role->add_cap( 'delete_' . $plural );
	$role->add_cap( 'delete_others_' . $singular );
	$role->add_cap( 'delete_published_' . $singular );
	$role->add_cap( 'delete_private_' . $singular );
	$role->add_cap( 'publish_' . $singular );
	$role->add_cap( 'moderate_comments_' . $singular );

	return $role;
}