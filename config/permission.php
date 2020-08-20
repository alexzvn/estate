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
            '*' => 'Tất cả quyền hạn',

            'login.multiple.devices' => 'Đăng nhập nhiều thiết bị',
            'post.province.access.*' => 'Xem bài bất kỳ thành phố',
            // 'post.district.access.*' => 'Xem bài từ bất kỳ Quận huyện',
            'post.category.access.*' => 'Xem bài bất kỳ danh mục',

            /**
             * ----------------------------------------------
             * Permission for customer goes here
             * ----------------------------------------------
             */
            'post.premium.sell.view' => 'Xem tin bán nhà đất',
            'post.premium.rent.view' => 'Xem tin thuê nhà đất',
            'post.premium.market.view' => 'Xem tin thị trường',
            'post.premium.sell_rent.view' => 'Xem tin cần mua, cần thuê',

            /**
             * ----------------------------------------------
             * Permission for manager goes here
             * ----------------------------------------------
             */
            'manager.dashboard.access' => 'Truy cập trang quản trị',

            'manager.plan.view' => 'Xem',
            'manager.plan.create' => 'Tạo mới',
            'manager.plan.modify' => 'Chỉnh sửa',
            'manager.plan.delete' => 'Xóa',

            'manager.category.view'   => 'Xem',
            'manager.category.create' => 'Tạo mới',
            'manager.category.modify' => 'Chỉnh sửa',
            'manager.category.delete' => 'Xóa',

            'manager.user.view'   => 'Xem',
            'manager.user.create' => 'Tạo mới',
            'manager.user.modify' => 'Chỉnh sửa',
            'manager.user.delete' => 'Xóa',
            'manager.user.assign.customer' => 'Gán người CSKH',

            'manager.customer.view'         => 'Xem thông tin',
            'manager.customer.log.view'     => 'Xem lịch sử hoạt động',
            'manager.customer.view.all'     => 'Xem thông tin khách bất kỳ',
            'manager.customer.modify'       => 'Cập nhật thông tin',
            'manager.customer.assign.role'  => 'Gán quyền cho khách',
            'manager.customer.create'       => 'Tạo tài khoản mới',
            'manager.customer.delete'       => 'Xóa tài khoản',
            'manager.customer.verify.phone' => 'Xác thực SĐT',
            'manager.customer.ban'          => 'Khóa tài khoản',
            'manager.customer.pardon'       => 'Mở khóa tài khoản',
            'manager.customer.take'     => 'Nhận QL khách',
            'manager.customer.logout'       => 'Thoát đăng nhập',

            'manager.subscription.delete'   => 'Xóa gói đăng ký',
            'manager.subscription.lock'     => 'Khóa gói đăng ký',

            'manager.order.view'   => 'Xem',
            'manager.order.create' => 'Tạo mới',
            'manager.order.modify' => 'Chỉnh sửa',
            'manager.order.delete' => 'Xóa',
            'manager.order.modify.force' => 'Chỉnh sửa khi đã xác thực',

            'manager.role.view'   => 'Xem',
            'manager.role.create' => 'Tạo mới',
            'manager.role.modify' => 'Chỉnh sửa',
            'manager.role.delete' => 'Xóa',

            'manager.post.view'   => 'Xem',
            'manager.post.create' => 'Tạo mới',
            'manager.post.modify' => 'Chỉnh sửa',
            'manager.post.delete' => 'Xóa',

            'manager.post.report.view' => 'Xem thông tin báo MG',
            'manager.post.report.delete' => 'Xóa thông ti báo MG',

            'blacklist.phone.view' => 'Xem',
            'blacklist.phone.create' => 'Thêm SĐT',
            'blacklist.phone.modify' => 'Chỉnh sửa',
            'blacklist.phone.delete' => 'Bỏ chặn',

            'manager.site.setting' => 'Cẫu hình trang web',

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
                    'manager.user.assign.customer',
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
                'name' => 'Quản lý khách hàng',
                'permissions' => [
                    'manager.customer.view',
                    'manager.customer.view.all',
                    'manager.customer.assign.role',
                    'manager.customer.modify',
                    'manager.customer.create',
                    'manager.customer.verify.phone',
                    'manager.customer.ban',
                    'manager.customer.pardon',
                    'manager.subscription.delete',
                    'manager.subscription.lock',
                    'manager.customer.take',
                    'manager.customer.log.view',
                    'manager.customer.logout'
                ]
            ],[
                'name' => 'Gói đăng ký', 
                'permissions' => [
                    'manager.plan.view',
                    'manager.plan.create',
                    'manager.plan.modify',
                    'manager.plan.delete',
                ]
            ],[
                'name' => 'Quản lý đơn hàng',
                'permissions' => [
                    'manager.order.view',
                    'manager.order.create',
                    'manager.order.modify',
                    'manager.order.modify.force',
                    'manager.order.delete',
                ]
            ],[
                'name' => 'Chặn số điện thoại',
                'permissions' => [
                    'blacklist.phone.view',
                    'blacklist.phone.create',
                    'blacklist.phone.modify',
                    'blacklist.phone.delete',
                ]
            ],[
                'name' => 'Khác',
                'permissions' => [
                    '*',
                    'manager.dashboard.access',
                    'login.multiple.devices',
                    'post.province.access.*',
                    'post.category.access.*',
                    'manager.site.setting',
                ]
            ]
        ],

        'roles' => [
            [
                'name' => 'Super Admin',
                'permissions' => ['*'],
            ],[
                'name' => 'Nhân viên',
                'permissions' => [
                    'manager.dashboard.access',
                    'login.multiple.devices'
                ]
            ]
        ],
    ],
];
