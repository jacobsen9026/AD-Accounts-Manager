<?php
/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>




<?php

use app\models\district\District;

if ($this->config->district->getDistrict() == null) {
    ?>
    <form method="post" name="test" class ="table-hover" action="/settings/createDistrict">
        <div class="container container-lg">
            <div>
                Create District
            </div>
            <div>Name:<input type="text" name="name"/></div>
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
    <?php
} else {

    /* @var $district District */
    $district = $this->config->district->getDistrict();
    ?>

    <div class="header">District:<br/><?php echo $district->name; ?></div>
    <form method="post" name="test" class ="table-hover" action="/settings/editDistrict">
        <label for="abbreviation">Abbreviation</label><input name="abbreviation" type="text"/>
        <div>Grade Span</div>
        <label for="gradeFrom">From</label><select name="gradeFrom">
            <option value ="PK3">PK3</option>

            <option value ="PK4">PK4</option>

            <option value ="K">K</option>
            <option value ="1">1</option>
            <option value ="2">2</option>
            <option value ="3">3</option>
            <option value ="4">4</option>
            <option value ="5">5</option>
            <option value ="6">6</option>
            <option value ="7">7</option>
            <option value ="8">8</option>
            <option value ="9">9</option>
            <option value ="10">10</option>
            <option value ="11">11</option>
        </select>
        <label for="gradeTo">From</label><select name="gradeTo">

            <option value ="PK4">PK4</option>

            <option value ="K">K</option>
            <option value ="1">1</option>
            <option value ="2">2</option>
            <option value ="3">3</option>
            <option value ="4">4</option>
            <option value ="5">5</option>
            <option value ="6">6</option>
            <option value ="7">7</option>
            <option value ="8">8</option>
            <option value ="9">9</option>
            <option value ="10">10</option>
            <option value ="11">11</option>
            <option value ="12">12</option>
        </select>
        <button class="btn btn-primary" type="submit">Submit</button>
    </form>




    <div class="header">Schools:<br/></div>

    <div class="table-responsive-sm">
        <table class="mx-auto table table-hover">
            <thead class="thead-light">
                <tr>
                    <td>
                        School Name
                    </td>
                    <td>
                        Grades
                    </td>
                    <td>
                        Edit
                    </td>
                    <td>
                        Remove
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($district->schools as $school) {
                    var_dump($school);
                    ?>
                    <tr>
                        <td><?php echo $school->name; ?></td><td>

                        </td>
                        <td>
                            <button class = "btn btn-primary">Edit School</button>
                        </td>
                        <td>

                            <button class = "btn btn-primary">Remove School</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>



        </table>
    </div>
    <form method = "post" name = "test" class = "table-hover" action = "/settings/addSchool">
        <label for = "name">School Name</label><input name = "name" type = "text"/>
        <button class = "btn btn-primary" type = "submit">Add School</button>
    </form>
    <?php
    /**
     *
     * <form method="post" name="test" class ="table-hover">
      <div class="yogList settingsList tableList">
      <div>
      <div>
      <h3>
      Grade
      </h3>
      </div>
      <div>
      <h3>
      Year of Graduation
      </h3>
      </div>
      </div>
      <div>
      <div>
      8
      </div>
      <div>
      <input type="text" name="yog8" value="<?php echo $appConfig["gradeMappings"]["8"]; ?>" placeholder="<?php echo $year + 5; ?>">
      </div>
      </div>
      <div>
      <div>
      7
      </div>
      <div>
      <input type="text" name="yog7" value="<?php echo $appConfig["gradeMappings"]["7"]; ?>" placeholder="<?php echo $year + 6; ?>">
      </div>
      </div>
      <div>
      <div>
      6
      </div>
      <div>
      <input type="text" name="yog6" value="<?php echo $appConfig["gradeMappings"]["6"]; ?>" placeholder="<?php echo $year + 7 ?>">
      </div>
      </div>
      <div>
      <div>
      5
      </div>
      <div>
      <input type="text" name="yog5" value="<?php echo $appConfig["gradeMappings"]["5"]; ?>" placeholder="<?php echo $year + 8; ?>">
      </div>
      </div>
      <div>
      <div>
      4
      </div>
      <div>
      <input type="text" name="yog4" value="<?php echo $appConfig["gradeMappings"]["4"]; ?>" placeholder="<?php echo $year + 9; ?>">
      </div>
      </div>
      <div>
      <div>
      3
      </div>
      <div>
      <input type="text" name="yog3" value="<?php echo $appConfig["gradeMappings"]["3"]; ?>" placeholder="<?php echo $year + 10 ?>">
      </div>
      </div>
      <div>
      <div>
      2
      </div>
      <div>
      <input type="text" name="yog2" value="<?php echo $appConfig["gradeMappings"]["2"]; ?>" placeholder="<?php echo $year + 11; ?>">
      </div>
      </div>
      <div>
      <div>
      1
      </div>
      <div>
      <input type="text" name="yog1" value="<?php echo $appConfig["gradeMappings"]["1"]; ?>" placeholder="<?php echo $year + 12; ?>">
      </div>
      </div>
      <div>
      <div>
      K
      </div>
      <div>
      <input type="text" name="yogk" value="<?php echo $appConfig["gradeMappings"]["K"]; ?>" placeholder="<?php echo $year + 13; ?>">
      </div>
      </div>
      <div>
      <div>
      PK4
      </div>
      <div>
      <input type="text" name="yogpk4" value="<?php echo $appConfig["gradeMappings"]["PK4"]; ?>" placeholder="<?php echo $year + 14; ?>">
      </div>
      </div>
      <div>
      <div>
      PK3
      </div>
      <div>
      <input type="text" name="yogpk3" value="<?php echo $appConfig["gradeMappings"]["PK3"]; ?>" placeholder="<?php echo $year + 15; ?>">

      </div>
      </div>
      <?php
      if (isset($_GET["rolloverGrades"])) {
      echo"<div><div></div><div><div class='alert'>Grades Rolled Over Succefully!</div></div></div>";
      }
      ?>
      </div>

      <br/>
      <input type="hidden" name="rolloverGrades" value="true"/>
      <a href="<?php echo $pageURL; ?>&rolloverGrades=true">
      <button type="button">
      Rollover YOG's
      </button>
      </a>
      </form>
     */
}
?>



