<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'email' => 'unit@test.com',
                'password' => password_hash('12345678', PASSWORD_BCRYPT),
            ]
        ];

        $users = $this->table('users');
        $users->insert($data)
            ->saveData();

    }
}
