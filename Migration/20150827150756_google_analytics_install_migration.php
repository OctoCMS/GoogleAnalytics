<?php

use Phinx\Migration\AbstractMigration;

class GoogleAnalyticsInstallMigration extends AbstractMigration
{
    public function up()
    {
        // Create tables:
        $this->createGaPageView();
        $this->createGaSummaryView();
        $this->createGaTopPage();

        // Add foreign keys:
        $table = $this->table('ga_top_page');

        if (!$table->hasForeignKey('page_id') && $this->hasTable('page')) {
            $table->addForeignKey('page_id', 'page', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE']);
            $table->save();
        }
    }

    protected function createGaPageView()
    {
        $table = $this->table('ga_page_view', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('ga_page_view')) {
            $table->addColumn('id', 'integer', ['signed' => false, 'null' => false]);
            $table->create();
        }

        if (!$table->hasColumn('date')) {
            $table->addColumn('date', 'date', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('updated')) {
            $table->addColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('value')) {
            $table->addColumn('value', 'integer', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('metric')) {
            $table->addColumn('metric', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasIndex(['metric', 'date'])) {
            $table->addIndex(['metric', 'date'], ['unique' => true]);
        }

        $table->save();

        $table->changeColumn('date', 'date', ['null' => true, 'default' => null]);
        $table->changeColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('value', 'integer', ['null' => true, 'default' => null]);
        $table->changeColumn('metric', 'string', ['limit' => 250, 'null' => true, 'default' => null]);

        $table->save();
    }

    protected function createGaSummaryView()
    {
        $table = $this->table('ga_summary_view', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('ga_summary_view')) {
            $table->addColumn('id', 'integer', ['signed' => false, 'null' => false]);
            $table->create();
        }

        if (!$table->hasColumn('updated')) {
            $table->addColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('value')) {
            $table->addColumn('value', 'integer', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('metric')) {
            $table->addColumn('metric', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasIndex(['metric'])) {
            $table->addIndex(['metric'], ['unique' => true]);
        }

        $table->save();

        $table->changeColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('value', 'integer', ['null' => true, 'default' => null]);
        $table->changeColumn('metric', 'string', ['limit' => 250, 'null' => true, 'default' => null]);

        $table->save();
    }

    protected function createGaTopPage()
    {
        $table = $this->table('ga_top_page', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('ga_top_page')) {
            $table->addColumn('id', 'integer', ['signed' => false, 'null' => false]);
            $table->create();
        }

        if (!$table->hasColumn('updated')) {
            $table->addColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('pageviews')) {
            $table->addColumn('pageviews', 'integer', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('unique_pageviews')) {
            $table->addColumn('unique_pageviews', 'integer', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('uri')) {
            $table->addColumn('uri', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('page_id')) {
            $table->addColumn('page_id', 'char', ['limit' => 5, 'null' => true, 'default' => null]);
        }

        if (!$table->hasIndex(['uri'])) {
            $table->addIndex(['uri'], ['unique' => true]);
        }

        $table->save();

        $table->changeColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('pageviews', 'integer', ['null' => true, 'default' => null]);
        $table->changeColumn('unique_pageviews', 'integer', ['null' => true, 'default' => null]);
        $table->changeColumn('uri', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $table->changeColumn('page_id', 'char', ['limit' => 5, 'null' => true, 'default' => null]);

        $table->save();
    }
}
