<?php

namespace Tnt\Redirects\Revisions;

use Oak\Contracts\Migration\RevisionInterface;
use Tnt\Dbi\TableBuilder;

class UpdateRedirectTableAddUniqueSourcePath extends DatabaseRevision implements RevisionInterface
{
    /**
     * Create redirects_redirect table
     */
    public function up()
    {
        $this->queryBuilder->table('redirects_redirect')->alter(function(TableBuilder $table) {
            $table->addUnique('source_path');
        });

        $this->execute();
    }

    /**
     * Drop redirects_redirect table
     */
    public function down()
    {
        $this->queryBuilder->table('redirects_redirect')->alter(function(TableBuilder $table) {
            $table->dropUnique('source_path');
        });

        $this->execute();
    }

    /**
     * @return string
     */
    public function describeUp(): string
    {
        return 'Update redirects_redirect table add unique source_path';
    }

    /**
     * @return string
     */
    public function describeDown(): string
    {
        return 'Update redirects_redirect table drop unique source_path';
    }
}
