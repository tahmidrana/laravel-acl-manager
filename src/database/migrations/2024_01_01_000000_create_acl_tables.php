<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'is_superuser')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_superuser')->default(false)->after('password');
            });
        }

        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('route_name')->nullable();
            $table->string('menu_url')->nullable();
            $table->string('menu_icon')->nullable();
            $table->unsignedSmallInteger('menu_order')->default(1);
            $table->unsignedSmallInteger('parent_menu_id')->nullable();
            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 150);
            $table->string('slug', 150)->unique()->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('controller_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('menu_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');;
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');;
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->dateTime('released_at')->nullable();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    public function down(): void {
        if (Schema::hasColumn('users', 'is_superuser')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_superuser');
            });
        }

        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('menus');
    }
};
