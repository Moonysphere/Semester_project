<?

namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;
use App\Lib\Annotations\ORM\References;


#[ORM]
class Character extends AbstractEntity
{

    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $name;

    #[Column(type: 'varchar', size: 255)]
    public string $slug;

    #[Column(type: 'int')]
    #[References(class: Role::class, property: 'id')]
    public int $role_id;

    #[Column(type: 'varchar', size: 255)]
    public string $origin;

    #[Column(type: 'int')]
    public int $pv;

    #[Column(type: 'varchar', size: 255)]
    public string $description;

    #[Column(type: 'varchar', size: 255)]
    public string $status;

    #[Column(type: 'int')]
    #[References(class: Univers::class, property: 'id')]
    public int $univers_id;

    public function getId(): int
    {
        return $this->id;
    }
}
