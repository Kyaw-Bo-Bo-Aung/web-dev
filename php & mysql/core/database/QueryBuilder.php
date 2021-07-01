<?php

class QueryBuilder{
	protected $pdo;
	public function __construct($conn) {
		$this->pdo = $conn;
	}

	public function selectAll($table) {
		$sql = "SELECT * FROM ".$table;
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert($dataArray, $table){
		$dataKeyArr = array_keys($dataArray);
		$dataKey = implode(',',$dataKeyArr);
		// dd($dataKey);
		$dataValueArr = array_values($dataArray);

		$questionMark;
		foreach ($dataKeyArr as $q) {
			$questionMark .= '?,';
		}
		$questionMark = rtrim($questionMark,',');		

		$sql = "INSERT INTO $table ($dataKey) VALUES ($questionMark)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($dataValueArr);

	}

	public function destroy($id, $table){

		$sql = "DELETE FROM $table WHERE id=$id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
	}
}