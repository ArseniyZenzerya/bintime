<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateTasksTable extends Migration
    {
        public function up()
        {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('description');
                $table->enum('status', ['New', 'In Progress', 'Failed', 'Finished']);
                $table->timestamp('start_at');
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('tasks');
        }
    }
