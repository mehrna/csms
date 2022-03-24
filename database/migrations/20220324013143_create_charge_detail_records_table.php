<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateChargeDetailRecordsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $cdr = $this->table('charge_detail_records');
        $cdr->changeColumn('id', 'biginteger', ['identity' => true])
            ->addColumn('rate_id', 'integer', ['null' => true])
            ->addColumn('meter_start', 'biginteger')
            ->addColumn('timestamp_start', 'timestamp')
            ->addColumn('meter_stop', 'biginteger')
            ->addColumn('timestamp_stop', 'timestamp')
            ->addTimestamps()
            ->addForeignKey('rate_id', 'rates', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->create();
    }
}
