<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 21/09/15
 * Time: 22:28
 */

namespace TaskManager\Transformers;

use TaskManager\Entities\User;
use League\Fractal\TransformerAbstract;

class ProjectMemberTransformer extends TransformerAbstract
{

    public function transform(User $member)
    {
        return[
            'member_id' => $member->id,
            'member_name' => $member->name,
            'member_email' => $member->email,
        ];
    }

}