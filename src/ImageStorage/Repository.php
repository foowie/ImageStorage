<?php

namespace ImageStorage;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Repository extends \Nette\Object {

	/** @var \Nette\Database\Connection */
	protected $conn;

	/** @var string */
	protected $tableName;

	function __construct(\Nette\Database\Connection $conn, $tableName = 'image') {
		$this->conn = $conn;
		$this->tableName = $tableName;
	}

	public function find($id) {
		return $this->table()->get($id);
	}

	public function create($data) {
		$entity = $this->table()->insert($data);
		if ($entity === false)
			throw new \Nette\InvalidStateException();
		return $entity;
	}

	public function update($id, $data) {
		$entity = $this->find($id);
		if ($entity === false)
			throw new \Nette\InvalidStateException('Entity ' . $this->tableName . ' with ID ' . $id . ' not found!');
		foreach ($data as $key => $value)
			$entity->$key = $value;
		$count = $entity->update();
		if ($count === false)
			throw new \Nette\InvalidStateException();
		return $entity;
	}

	public function delete($id) {
		$row = $this->find($id);
		if ($row === false)
			throw new \Nette\InvalidStateException('Entity ' . $this->tableName . ' with ID ' . $id . ' not found!');
		$entity = $row->delete();
		if ($entity === false)
			throw new \Nette\InvalidStateException();
		return $entity;
	}

	/**
	 * @return \Nette\Database\Table\Selection
	 */
	protected function table() {
		return $this->conn->table($this->tableName);
	}

}
