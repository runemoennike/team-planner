<h1><?php echo empty($this->person->id) ? 'Add person' : 'Edit person' ?></h1>

<ul>
    <li><a href="/">Home</a></li>
    <li><a href="<?php echo URL_BASE.'people/list' ?>">People</a></li>
</ul>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="name">Name</label>
        <input name="name" id="name" value="<?php echo $this->person->name ?>" required />
    </div>

    <div>
        <label for="email">Email</label>
        <input name="email" id="email" value="<?php echo $this->person->email ?>" required />
    </div>

    <div>
        <label for="phone">Phone</label>
        <input name="phone" id="phone" value="<?php echo $this->person->phone ?>" />
    </div>

    <div>
        <label for="education">Education</label>
        <input name="education" id="education" value="<?php echo $this->person->education ?>" />
    </div>

    <div>
        <label for="hiredYear">Hired year</label>
        <input name="hiredYear" id="hiredYear" value="<?php echo $this->person->hiredYear ?>" required />
    </div>

    <div>
        <label for="skills">Skills</label>
        <select name="skills[]" id="skills" multiple>
            <?php foreach($this->skills as $skill) : ?>
                <option value="<?php echo $skill->id ?>" <?php if($this->person->hasSkill($skill->id)) echo 'selected' ?>><?php echo $skill->name ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div>
        <label for="technologies">Technologies</label>
        <select name="technologies[]" id="technologies" multiple>
            <?php foreach($this->technologies as $technology) : ?>
                <option value="<?php echo $technology->id ?>" <?php if($this->person->hasTechnology($technology->id)) echo 'selected' ?>><?php echo $technology->name ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div>
        <label for="image">Profile image</label>
        <input type="file" name="image" id="image" />
    </div>

    <input type="submit" />
</form>
