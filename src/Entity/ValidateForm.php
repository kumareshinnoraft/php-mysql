<?php

namespace App\Entity;

/**
 *  This class validate user input and verify whather inserted values 
 *  format is correct.
 * 
 *  @method filterUserData()
 *    this method filters user inserted data.
 *  @method extractMarksSubjects()
 *    this method extract subjects and marks.
 *    
 *  @property array $subjects
 *    This array stores the subjects.
 *  @property array $marks
 *    This array stores marks.
 * 
 *  @author Kumaresh Baksi <kumaresh.baksi@innoraft.com>
 */
class ValidateForm
{
  /**
   *  subjects array will store all extracted subjects.
   *
   *  @var array
   */
  public $subjects = [];
  /**
   *  marks array will store all extracted marks.
   *
   *  @var array
   */
  public $marks = [];

  /**
   *  filterUserData function filter use data in backend it checks the
   *  validaty of the text
   *
   *  @param  string $firstName
   *    Firstname text of the user.
   *  @param  string $lastName
   *    Lastname text entered by the user.
   *  @param  string $marksAndsubject
   *    This text contains both marks and subjects.
   * 
   *  @return mixed
   *    If any errors occur a string message will be returned instead an array
   *    which will contain array of marks and subjects.
   */
  public function filterUserData(string $firstName, string $lastName, string $marksAndsubject)
  {
    if (preg_match('~[0-9]+~', $firstName) || (preg_match('~[0-9]+~', $lastName))) {
      // Checks if name contains number.
      return "Name should not contain number";
    } elseif ($this->extractMarksSubjects($marksAndsubject)) {
      // If marks and subjects are not included in a proper way.
      return "Marks and subject is not in the right way";
    }
    // If all conditions get satisfied an array will be returned.
    $subWithMarks = [$this->subjects, $this->marks];
    return $subWithMarks;
  }

  /**
   *  extractMarksSubjects method returns a boolean value.
   *
   *  @param  string $subTextArea
   *    This string contains subject and marks both.
   * 
   *  @return boolean
   *   if marks and subjects are entered by the user in a proper way,
   *   it will reeturn TRUE instrad FALSE.
   */
  public function extractMarksSubjects(string $subTextArea)
  {
    $i = 0;
    // Extracting the new lines
    $subjectWithMarks = explode("\n", $subTextArea);

    foreach ($subjectWithMarks as $data) {
      if (!preg_match('/^[a-zA-Z]+\|[0-9]+$/', trim($data))) {
        return TRUE;
      } else {
        $temp = explode("|", $data);
        $this->subjects[$i] = trim($temp[0]);
        $this->marks[$i] = trim($temp[1]);
        $i++;
      }
    }
    return FALSE;
  }
}