echo Ten bang cua ban la gi?
Set /p table=
OKAY! dang tao du lieu %table% ...

php artisan make:model %table% -m
php artisan make:seed Create%table%Seeder
php artisan make:controller Admin/%table%Controller --model=%table%

php artisan make:viewadd %table%
php artisan make:viewedit %table%
php artisan make:viewindex %table%
php artisan make:viewheader %table%
php artisan make:viewsearch %table%

php artisan make:_policy %table%

echo DONE!
echo ---PhamSon---