<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('categories', function (Blueprint $table) {
            // 'category_name' カラム名を 'name' に変更
            $table->renameColumn('category_name', 'name');
        });
        // カテゴリーテーブルにデフォルトカテゴリーを追加
        DB::table('categories')->insert([
            'name' => 'タスク',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
