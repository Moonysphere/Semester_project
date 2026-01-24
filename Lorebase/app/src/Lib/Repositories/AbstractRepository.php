<?php

namespace App\Lib\Repositories;

use App\Lib\Database\DatabaseConnexion;
use App\Lib\Database\Dsn;
use App\Lib\Entities\AbstractEntity;

abstract class AbstractRepository
{
    protected DatabaseConnexion $db;
    protected string $queryString;
    protected string $tableAlias;
    protected array $params = [];
    protected \PDOStatement $query;

    const CONDITIONS = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'like' => 'LIKE',
        'in' => 'IN'
    ];

    public function __construct()
    {
        $dsn = new Dsn();
        $dsn->addHostToDsn();
        $dsn->addPortToDsn();
        $dsn->addDbnameToDsn();

        $db = new DatabaseConnexion();
        $db->setConnexion($dsn);

        $this->db = $db;
    }

    public function getTable(): string
    {
        return str_replace('repository', '', strtolower((new \ReflectionClass($this))->getShortName()));
    }

    private function getFields(AbstractEntity $entity): string
    {
        $fields = [];
        foreach ($entity->toArray() as $key => $value) {
            $fields[] = $key;
        }

        return implode(', ', $fields);
    }

    private function getValues(AbstractEntity $entity): string
    {
        $values = [];
        foreach ($entity->toArray() as $key => $value) {
            $values[] = ':' . $key;
        }

        return implode(', ', $values);
    }

    public function queryBuilder(): self
    {
        $this->queryString = "";
        $this->params = [];
        return $this;
    }

    public function select(...$fields): self
    {
        $this->queryString .= "SELECT";

        if (count($fields) === 0) {
            $this->queryString .= ' *';
            return $this;
        }

        $this->queryString .= ' ' . implode(', ', $fields);
        return $this;
    }

    public function findBySlug(string $slug , string $table): ?AbstractEntity
{
    return $this->queryBuilder()
        ->select('*')
        ->from($table)
        ->where('slug', '=')
        ->addParam('slug', $slug)
        ->executeQuery()
        ->getOneResult();
}


    public function insert(AbstractEntity $entity): self
    {
        $this->queryString .= "INSERT INTO {$this->getTable()} ({$this->getFields($entity)})";
        return $this;
    }

    public function delete(): self
    {
        $this->queryString .= "DELETE";
        return $this;
    }

    public function updateTable(): self
    {
        $this->queryString .= "UPDATE {$this->getTable()}";
        return $this;
    }

    public function values(AbstractEntity $entity): self
    {
        $this->queryString .= " VALUES ({$this->getValues($entity)})";
        return $this;
    }

    public function from(string $tableAlias): self
    {
        $table = $this->getTable();
        $this->queryString .= " FROM $table";

        return $this->as($tableAlias);
    }

    public function as(string $tableAlias): self
    {
        $this->queryString .= " AS $tableAlias";
        $this->tableAlias = $tableAlias;
        return $this;
    }

    public function andWhere(string $field, string $condition, ?string $table = null): self
    {
        $this->queryString .= " AND  ";
        return $this->where($field, $condition, $table);
    }

    public function orWhere(string $field, string $condition, ?string $table = null): self
    {
        $this->queryString .= " OR  ";
        return $this->where($field, $condition, $table);
    }

    public function where(string $field, string $condition, ?string $table = null): self
    {
        $this->queryString .= " WHERE ";
        if ($table !== null) {
            $this->queryString .= "$table.";
        } else {
            $this->queryString .= "$this->tableAlias.";
        }

        $this->queryString .= "$field $condition :$field";
        return $this;
    }

    


private function normalizeParams(array $params): array
{
    foreach ($params as $k => $v) {
        if ($v instanceof \DateTimeInterface) {
            // type DATE
            $params[$k] = $v->format('Y-m-d');
        }
        return $params;
    }

    public function setParams(array $params): self
    {
        $this->params = $this->normalizeParams($params);
        return $this;
    }


    public function addParam(string $key, $value): self
    {
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d H:i:s');
        }
        $this->params[$key] = $value;
        return $this;
    }


    public function executeQuery(): self
    {
        $this->query = $this->db->getConnexion()->prepare($this->queryString);

        $this->query->execute($this->params);
        return $this;
    }
   public function first(): array|false
{

    $row = $this->query->fetch(\PDO::FETCH_ASSOC);

    $this->queryBuilder();

    return $row ?: false;
}


   public function getOneResult()
{
    $row = $this->query->fetch(\PDO::FETCH_ASSOC);
    if ($row === false) return null;

    $class = 'App\\Entities\\' . ucfirst($this->getTable());
    $entity = new $class();

    foreach ($row as $key => $value) {
        if ($key === 'createdate' && $value !== null && $value !== '') {
            $entity->$key = new \DateTimeImmutable($value);
        } else {
            $entity->$key = $value;
        }
    }

    return $entity;
}

  public function getAllResults(): array
{
    $rows = $this->query->fetchAll(\PDO::FETCH_ASSOC);

    $class = 'App\\Entities\\' . ucfirst($this->getTable());

        $class = 'App\\Entities\\' . ucfirst($this->getTable());
        $entity = new $class();

        foreach ($row as $key => $value) {
            if ($key === 'createdate' && $value !== null && $value !== '') {
                $entity->$key = new \DateTimeImmutable($value);
            } else {
                $entity->$key = $value;
            }
        }

        return $entity;
    }


    public function getAllResults(): array
    {
        $rows = $this->query->fetchAll(\PDO::FETCH_ASSOC);

        $class = 'App\\Entities\\' . ucfirst($this->getTable());

        return array_map(function (array $row) use ($class) {
            $entity = new $class();

            foreach ($row as $key => $value) {
                if ($key === 'createdate' && $value !== null && $value !== '') {
                    $entity->$key = new \DateTimeImmutable($value);
                } else {
                    $entity->$key = $value;
                }
            }

            return $entity;
        }, $rows);
    }

    public function find(string | int $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findBy(array $criteria)
    {
        $this->queryBuilder()
            ->select()
            ->from(substr($this->getTable(), 0, 1))
        ;

        $this->addWhereAccordingToCriterias($criteria);

        return $this->executeQuery()
            ->getAllResults();
    }

    public function findOneBy(array $criteria)
    {
        $this->queryBuilder()
            ->select()
            ->from(substr($this->getTable(), 0, 1))
        ;

        $this->addWhereAccordingToCriterias($criteria);

        $data = $this->executeQuery()
            ->getOneResult();

        if ($data === false) {
            return null;
        }

        return $data;
    }

    private function addWhereAccordingToCriterias(array $criterias)
    {
        foreach ($criterias as $key => $value) {
            if (strpos($this->queryString, 'WHERE') === false) {
                $this->where($key, self::CONDITIONS['eq']);
            } else {
                $this->andWhere($key, self::CONDITIONS['eq']);
            }
            $this->addParam($key, $value);
        }
    }

   public function slugify(string $slug)
{

    $slug=strip_tags($slug);
    $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
    setlocale(LC_ALL, 'en_US.utf8');
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    $slug = trim($slug, '-');
    $slug = preg_replace('~-+~', '-', $slug);
    $slug = strtolower($slug);
    if (empty($slug)) { return 'n-a'; }
    return $slug;
}

    public function checkSlug(string $field, string $table,string $slug): string
{
    $baseSlug = $slug;
    $count = 1;

    $result = $this->queryBuilder()
        ->select($field)
        ->from($table)
        ->where('slug', '=');         
        $this->params[':slug'] = $slug;    
$result = $result->executeQuery()->first();

    while ($result) {
        $slug = $baseSlug . '-' . $count;
        $count++;

        $result = $this->queryBuilder()
           ->select($field)
           ->from($table)
           ->where('slug', '=');         
           $this->params[':slug'] = $slug;    
$result = $result->executeQuery()->first();
    }

    return $slug;
}

    

   public function set(AbstractEntity $entity): self
{
    $this->queryString .= " SET";
    foreach ($entity->toArray() as $key => $value) {
        if ($key === 'id') continue; 
        $this->queryString .= " $key = :$key,";
    }


    public function save(AbstractEntity $entity): string
    {
        $this->queryBuilder()
            ->insert($entity)
            ->values($entity)
            ->setParams($entity->toArray())
        ;

        $this->executeQuery();
        return $this->db->getConnexion()->lastInsertId();
    }

    public function update(AbstractEntity $entity): void
    {
        $this->queryBuilder()
            ->updateTable()
            ->as(substr($this->getTable(), 0, 1))
            ->set($entity)
            ->where('id', self::CONDITIONS['eq'])
            ->setParams($entity->toArray())
            ->executeQuery();
    }


    public function remove(AbstractEntity $entity)
    {
        $this->queryBuilder()
            ->delete()
            ->from($this->getTable())
            ->where('id', self::CONDITIONS['eq'])
            ->addParam('id', $entity->getId())
            ->executeQuery();
    }

    public function setField(string $field, string $paramName): self
    {
        $this->queryString .= " SET $field = :$paramName";
        return $this;
    }

    public function setStatut(int $id, string $status) : void
    {
        $this->queryBuilder()
            ->updateTable()
            ->as(substr($this->getTable(), 0, 1))
            ->setField('status', 'status')
            ->where('id', self::CONDITIONS['eq'])
            ->setParams([
                'id' => $id,
                'status' => $status
            ])
      
            ->executeQuery();
    }

}
