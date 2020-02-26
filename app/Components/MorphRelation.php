<?php
/**
 * Created by PhpStorm.
 * User: frienze
 * Date: 10.09.18
 * Time: 14:41
 */

namespace App\Components;

use App\Models\Bundle;
use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use InvalidArgumentException;

class MorphRelation
{
    const EXAMPLE   = 'example';

    /**
     * set a custom type
     */
    public static function setup()
    {
        Relation::morphMap(static::getConfig());
    }

    /**
     * get config
     *
     * @return array
     */
    public static function getConfig()
    {
        return [
            
        ];
    }

    /**
     * get model by type
     *
     * @param $type
     *
     * @return Group|Bundle|Contact
     */
    public static function getModel($type)
    {
        $config = static::getConfig();

        if (!isset($config[ $type ]) || !class_exists($config[ $type ])) {
            throw new ModelNotFoundException();
        }

        return new $config[ $type ];
    }

    /**
     * @param Model $model
     *
     * @return false|int|string
     */
    public static function getType(Model $model)
    {
        $config = static::getConfig();
        $type   = array_search(get_class($model), $config);

        if ($type === false) {
            throw new InvalidArgumentException();
        }

        return $type;
    }
}
