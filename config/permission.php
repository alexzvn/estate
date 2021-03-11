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


    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */

        'model_morph_key' => 'model_id',
    ],

    /*
     * When set to true, the required permission names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    /*
     * When set to true, the required role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_role_in_exception' => false,

    /*
     * By default wildcard permission lookups are disabled.
     */

    'enable_wildcard_permission' => false,

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'spatie.permission.cache',

        /*
         * When checking for a permission against a model by passing a Permission
         * instance to the check, this key determines what attribute on the
         * Permissions model is used to cache against.
         *
         * Ideally, this should match your preferred way of checking permissions, eg:
         * `$user->can('view-posts')` would be 'name'.
         */

        'model_key' => 'name',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

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
            'manager.customer.take'         => 'Nhận QL khách',
            'manager.customer.logout'       => 'Thoát đăng nhập',

            'manager.subscription.delete'   => 'Xóa gói đăng ký',
            'manager.subscription.lock'     => 'Khóa gói đăng ký',

            'manager.order.view'   => 'Xem',
            'manager.order.create' => 'Tạo mới',
            'manager.order.modify' => 'Chỉnh sửa',
            'manager.order.delete' => 'Xóa',
            'manager.order.modify.force' => 'Chỉnh sửa khi đã xác thực',
            'manager.order.phone.view' => 'Xem sđt',

            'manager.role.view'   => 'Xem',
            'manager.role.create' => 'Tạo mới',
            'manager.role.modify' => 'Chỉnh sửa',
            'manager.role.delete' => 'Xóa',

            'manager.post.online.view'    => 'Xem',
            'manager.post.online.create'  => 'Tạo mới',
            'manager.post.online.modify'  => 'Chỉnh sửa',
            'manager.post.online.delete'  => 'Xóa',
            'manager.post.online.clone'   => 'Duyệt qua tin xin phí',
            'manager.post.online.reserve' => 'Đảo tin',

            'manager.post.fee.view'   => 'Xem',
            'manager.post.fee.view.all' => 'Xem tất',
            'manager.post.fee.create' => 'Tạo mới',
            'manager.post.fee.modify' => 'Chỉnh sửa',
            'manager.post.fee.delete' => 'Xóa',

            'manager.post.market.view'   => 'Xem',
            'manager.post.market.create' => 'Tạo mới',
            'manager.post.market.modify' => 'Chỉnh sửa',
            'manager.post.market.delete' => 'Xóa',

            'manager.post.report.view' => 'Xem thông tin báo MG',
            'manager.post.report.delete' => 'Xóa thông ti báo MG',

            'manager.notification.post.report' => 'Báo tin môi giới',
            'manager.notification.user.register' => 'Người dùng đăng ký mới',

            'manager.censorship.view' => 'Xem',
            'blacklist.phone.view' => 'Xem SĐT đen',
            'blacklist.phone.create' => 'Thêm SĐT đen',
            'blacklist.phone.modify' => 'Sửa SĐT đen',
            'blacklist.phone.delete' => 'Bỏ chặn SĐT đen',
            'blacklist.phone.sms'    => 'Đếm SMS SĐT đen',
            'whitelist.phone.create' => 'Thêm SĐT trắng',

            'manager.note.user.view' => 'Xem ghi chú của khách',

            'manager.site.setting' => 'Cẫu hình trang web',
            'manager.audit.view' => 'Xem audit',

        ],

        /**
         * Group permission by function and name
         */
        'groups' => [
            [
                'name' => 'Kiểm duyệt tin',
                'permissions' => [
                    'manager.censorship.view',
                    'blacklist.phone.view',
                    'blacklist.phone.create',
                    'blacklist.phone.modify',
                    'blacklist.phone.delete',
                    'blacklist.phone.sms',
                    'whitelist.phone.create'
                ]
            ],[
                'name' => 'Quản lý tin online',
                'permissions' => [
                    'manager.post.online.view',
                    'manager.post.online.create',
                    'manager.post.online.modify',
                    'manager.post.online.delete',
                    'manager.post.online.clone',
                    'manager.post.online.reserve'
                ]
            ],[
                'name' => 'Quản lý tin xin phí',
                'permissions' => [
                    'manager.post.fee.view',
                    'manager.post.fee.create',
                    'manager.post.fee.modify',
                    'manager.post.fee.delete',
                    'manager.post.fee.view.all'
                ]
            ],[
                'name' => 'Quản lý tin thị trường',
                'permissions' => [
                    'manager.post.market.view',
                    'manager.post.market.create',
                    'manager.post.market.modify',
                    'manager.post.market.delete',
                ]
            ],[
                'name' => 'Quản lý môi giới',
                'permissions' => [
                    'manager.post.report.view',
                    'manager.post.report.delete',
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
                    'manager.customer.logout',
                    'manager.customer.delete'
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
                    'manager.order.phone.view'
                ]
            ],[
                'name' => 'Lịch sử ghi chú KH',
                'permissions' => [
                    'manager.note.user.view'
                ]
            ],[
                'name' => 'Nhận thông báo',
                'permissions' => [
                    'manager.notification.post.report',
                    'manager.notification.user.register'
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
                    'manager.audit.view',
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
