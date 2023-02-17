-- Begin --
* Chạy cron mỗi phút để gửi email và thông báo

run chedule: php artisan schedule:run
-- End --

-- Begin --
** Create Model, Migration, Seeder, Controller, Policy and View **

* Chạy file batch "mcv.bat"

php artisan make:model Test -m
php artisan make:seed CreateTestSeeder
php artisan make:controller Admin/TestController --model=Test

php artisan make:viewadd Test
php artisan make:viewedit Test
php artisan make:viewindex Test
php artisan make:viewheader Test
php artisan make:viewsearch Test
php artisan make:_policy Test

* Thêm quyền vào file "config/permissions"
* Thêm check permission vào file "app/Services/PermissionGateAndPolicyAccess"
* Thêm route và middleware "routes/administrator/index"
* Thêm menu vào view "resources/views/administrator/components/slidebars.blade"
* Thêm các trường bảng migration
* Sau dó chạy

php artisan migrate
php artisan db:seed --class=CreatePermissionSeeder
-- End --

-- Begin --
* Đối với App có sử dụng thông báo, cần đăng ký topic "app" khi vào app
-- End --
