<?php

return [

    'models' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Maklad\Permission\Contracts\Permission` contract.
         */

        'permission' => \App\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Maklad\Permission\Contracts\Role` contract.
         */

        'role' => \App\Models\Role::class,

    ],

    'collection_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',
    ],

    /*
     * By default all permissions will be cached for 24 hours unless a permission or
     * role is updated. Then the cache will be flushed immediately.
     */

    'cache_expiration_time' => 60 * 24,

    /*
     * By default we'll make an entry in the application log when the permissions
     * could not be loaded. Normally this only occurs while installing the packages.
     *
     * If for some reason you want to disable that logging, set this value to false.
     */

    'log_registration_exception' => true,

    /*
     * When set to true, the required permission/role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    'sync' => [

        'permissions' => [
            'all' => 'Tất cả quyền hạn',

            /**
             * ----------------------------------------------
             * Permission for customer goes here
             * ----------------------------------------------
             */
            'post.premium.view' => 'Xem tin cho thuê',

            /**
             * ----------------------------------------------
             * Permission for manager goes here
             * ----------------------------------------------
             */
            'manager.dashboard.access' => 'Truy cập trang quản trị',

            'manager.category.view'   => 'Xem',
            'manager.category.create' => 'Tạo mới',
            'manager.category.modify' => 'Chỉnh sửa',
            'manager.category.delete' => 'Xóa',

            'manager.user.view'   => 'Xem',
            'manager.user.create' => 'Tạo mới',
            'manager.user.modify' => 'Chỉnh sửa',
            'manager.user.delete' => 'Xóa',

            'manager.role.view'   => 'Xem',
            'manager.role.create' => 'Tạo mới',
            'manager.role.modify' => 'Chỉnh sửa',
            'manager.role.delete' => 'Xóa',

            'manager.post.view'   => 'Xem',
            'manager.post.create' => 'Tạo mới',
            'manager.post.modify' => 'Chỉnh sửa',
            'manager.post.delete' => 'Xóa',

            'manager.staff.view'   => 'Xem',
            'manager.staff.create' => 'Tạo mới',
            'manager.staff.modify' => 'Chỉnh sửa',
            'manager.staff.delete' => 'Xóa',

        ],

        /**
         * Group permission by function and name
         */
        'groups' => [
            [
                'name' => 'Quản lý bài viết',
                'permissions' => [
                    'manager.post.view',
                    'manager.post.create',
                    'manager.post.modify',
                    'manager.post.delete',
                ]
            ],[
                'name' => 'Quản lý danh mục',
                'permissions' => [
                    'manager.category.view',
                    'manager.category.create',
                    'manager.category.modify',
                    'manager.category.delete',
                ]
            ],[
                'name' => 'Quản lý thành viên',
                'permissions' => [
                    'manager.user.view',
                    'manager.user.create',
                    'manager.user.modify',
                    'manager.user.delete',
                ]
            ],[
                'name' => 'Quản lý vai Trò',
                'permissions' => [
                    'manager.role.view',
                    'manager.role.create',
                    'manager.role.modify',
                    'manager.role.delete',
                ]
            ],[
                'name' => 'Quản lý nhân viên',
                'permissions' => [
                    'manager.staff.view',
                    'manager.staff.create',
                    'manager.staff.modify',
                    'manager.staff.delete',
                ]
            ],[
                'name' => 'Khác',
                'permissions' => [
                    'manager.dashboard.access'
                ]
            ]
        ],

        'roles' => [
            [
                'name' => 'Super Admin',
                'permissions' => ['all'],
            ],[
                'name' => 'Nhân viên',
                'permissions' => ['manager.dashboard.access']
            ]
        ],
    ],
];
