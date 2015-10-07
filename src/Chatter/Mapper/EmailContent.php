<?php  

namespace Chatter\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class EmailContent extends AbstractDbMapper
{
    protected $tableName    = 'static_email_content';

    public function getEmailContent($type)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('type', $type);

		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }
}

