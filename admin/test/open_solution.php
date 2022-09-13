<?php
// core configuration
include_once "../../config/core.php";
 
// check if logged in as admin
include_once "../login_checker.php";

// include classes
include_once '../../config/database.php';
include_once "../../objects/test.php";
include_once "../../objects/problem.php";
include_once "../../objects/result.php";

// markdown
include_once "md.php";

$error = true;
$valid_user = false;
$valid_test = false;
$valid_problem = false;

if(isset($_POST['tcode']) && isset($_POST['reg_no']))
{
    $error = false;

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    //test
    $test = new Test($db);
    $test->tcode = $_POST['tcode'];
    $valid_test = $test->tcodeExists();

    if($valid_test)
    {
        $valid_user = $test->initResultAdmin(new Result($db,$test->tcode),$_POST['reg_no']);

        $valid_problem = $test->initProblem(new Problem($db));

        if($valid_user && $valid_problem)
        {
            $best = $test->getBest();

            if($best) $best = md_format("```\n" . $best . "\n```");
            else $best = "<p class='text-danger'><b> This student has not made any submission.<br /><small>You can check below if the student has saved code in any language.</small></b></p>";

            $solns = $test->getSaved();

            foreach($solns as $lang=>$code)
            {
                if($code) $code = md_format("```\n" . $code . "\n```");
                else $code = "<p class='text-danger'><b> This student has not made any submission in {$lang}.<br /><small>OR the student has submitted blank code most recently.</small></b></p>";

                $solns[$lang] = $code;
            }
        }
    }
}

$page_title = "Submission Report";

include_once "../layout_head.php";

echo "<div class='col-md-12'>";

if($error)
{
    echo "<div class='alert alert-danger'>
            <h4>Some Error Occurred. Please close this tab, refresh the result page, and try again.</h4>
        </div>";
}
else
{
    if(!$valid_test || !$valid_problem)
    {
        echo "<div class='alert alert-danger'>
                <h4>Invalid Test or Problem</h4>
            </div>";
    }
    else
    {
        echo "<table class='table table-responsive'>
                <tr>
                    <td class='col-md-2'><b>Test</b></td>
                    <td>{$test->tname} ({$test->tcode})</td>
                </tr>
                <tr>
                    <td><b>Registration Number</b></td>
                    <td>{$_POST['reg_no']}</td>
                </tr>";

        if(!$valid_user)
        {
            echo "</table>
                  <div class='alert alert-danger'>
                    <h4>This student is no longer registered for the selected test.</h4>
                  </div>";
        }
        else
        {
            echo "<tr>
                    <td><b>Roll Number</b></td>
                    <td>{$test->rollNo()}</td>
                  </tr>
                </table>";

            $line = "<table class='table'><tr><td></td></tr></table>";

            echo $line;

            echo "<div class='alert alert-success'>
                    <h4><b>Best Submission</b></h4>
                    <div class='well well-sm'>{$best}</div>
                </div>";

            foreach($solns as $lang=>$code)
            {
                echo $line;

                echo "<div class='alert alert-info'>
                    <h4><b>{$lang} Latest Saved Code</b></h4>
                    <div class='well well-sm'>{$code}</div>
                </div>";

            }
        }
    }
}

echo "</div>";

include_once "../layout_foot.php";
?>