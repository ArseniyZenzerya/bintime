<?php

    namespace App\Models;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Support\Facades\Hash;

    class User extends Authenticatable
    {
        use HasFactory, Notifiable;

        protected $fillable = ['login', 'password', 'first_name', 'last_name', 'email', 'registered_at'];
        protected $hidden = ['password'];

        public function tasks()
        {
            return $this->hasMany(Task::class);
        }

        public static function createUser(array $data)
        {
            $data['password'] = Hash::make($data['password']);
            return self::create($data);
        }

        public function updateUser(array $data)
        {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $this->update($data);
            return $this;
        }
    }
