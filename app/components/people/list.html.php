<h1>People</h1>

<a href="<?php echo URL_BASE.'people/add' ?>">Add new person</a>

<table>
    <thead>
        <tr>
            <td></td>
            <td>Name</td>
            <td>Education</td>
            <td>Skills</td>
            <td>Technologies</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->people as $person) : ?>
            <tr>
                <td><img src="uploads/<?php echo $person->id ?>.jpeg" style="width:50px;height:50px;"></td>
                <td><?php echo $person->name ?></td>
                <td><?php echo $person->education ?></td>
                <td>
                    <?php if(!empty($person->skills)) : ?>
                        <?php foreach($person->skills as $skill) : ?>
                            <?php echo $skill->name ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
                <td>
                    <?php if(!empty($person->technologies)) : ?>
                        <?php foreach($person->technologies as $technology) : ?>
                            <?php echo $technology->name ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
                <td>
                    <a href="<?php echo URL_BASE.'people/edit'.URL_PARAM_START.'personId='.$person->id ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
