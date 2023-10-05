<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // デフォルトのカテゴリー名を「タスク」から「今日のやること」に変更
        DB::table('categories')
            ->where('name', 'タスク')
            ->update(['name' => '今日のやること']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ロールバック時の処理を「今日のやること」から「タスク」に変更
        DB::table('categories')
            ->where('name', '今日のやること')
            ->update(['name' => 'タスク']);
    }
};
