<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.14.
 * Time: 9:49
 *
 * @package oSocial
 * @subpacakage formHandler
 */
class form
{
    /**
     * Az alap funkciókhoz
     * @var coreFunctions|null
     */
    private $core = null;

    /**
     * Hiba kezelés
     * @var errorHandler|null
     */
    private $error = null;

    function __construct()
    {
        if (is_null($this->core)) {
            $this->core = new coreFunctions();
        }

        if (is_null($this->error)) {
            $this->error = new errorHandler();
        }
    }

    /**
     * Form generálás
     *
     * @todo SELECT, Radio, Checkbox elemek generálása
     * @param string $formTitle A Form neve
     * @param string $operator A Form ID-je
     * @param string $formArray A Formot létrehozó tömb
     * @param string $formAction A Form cselekvése (opcionális)
     * @return bool|null|string false ha hiba van|null ha nincs tartalom|string ha van tartalom
     */
    public function generateForm($formTitle = null, $operator = null, $formArray = null, $formAction = null)
    {
        if (is_array($formArray)) {

            $formContent = null;

            foreach ($formArray as $row) {

                if (isset($row['type'])) {
                    $name = (isset($row['name']) ? $row['type'] . '-' . $row['name'] : null);
                    $id = (isset($row['name']) ? $row['name'] : null);
                    $value = (isset($row['value']) ? $row['value'] : null);
                    $placeHolder = (isset($row['placeH']) ? $row['placeH'] : null);

                    switch ($row['type']) {
                        case 'text':
                            $formContent .= '<p><input class="textInput" id="' . $id . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" /></p>';
                            break;
                        case 'textArea':
                            $formContent .= '<p><textarea id="' . $id . '" name="' . $name . '" placeholder="' . $placeHolder . '">' . $value . '</textarea></p>';
                            break;
                        case 'email':
                            $formContent .= '<p><input class="textInput" id="' . $id . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" /></p>';
                            break;
                        case 'num':
                            $formContent .= '<p><input class="textInput" id="' . $id . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . $placeHolder . '" /></p>';
                            break;
                        case 'file';
                            break;
                        case 'pass':
                            $formContent .= '<p><input type="password" class="textInput" id="' . $id . '" name="' . $name . '" placeholder="' . $placeHolder . '"></p>';
                            break;
                        case 'date':
                            $formContent .= '<p><input type="text" class="textInput" id="' . $id . '" name="' . $name . '" placeholder="' . $placeHolder . '"></p>';
                            break;
                        case 'select':
                            $formContent .= '<p>';
                            $formContent .= '<select name="' . $name . '" id="' . $id . '">';

                            if (!is_null($placeHolder)) {
                                $formContent .= '<option>';
                                $formContent .= $placeHolder;
                                $formContent .= '</option>';
                            }

                            $options = explode('|;|', $value);

                            foreach ($options as $option) {

                                $optionData = explode('||', $option);

                                $formContent .= '<option value="' . $optionData[0] . '">';
                                $formContent .= $optionData[1];
                                $formContent .= '</option>';
                            }

                            $formContent .= '</select>';
                            $formContent .= '</p>';
                            break;
                        default:
                            break;
                    }
                }
            }

            if (!is_null($formContent)) {

                $titleSlug = (!is_null($operator) ? $this->core->slugger($operator, array(), '+') : 'form');

                $temp = '<form accept-charset="utf-8" action="' . $formAction . '" method="post" id="' . $titleSlug . '">';
                $temp .= $formContent;
                $temp .= '<input class="formButton" type="submit" name="submit-' . $titleSlug . '" value="' . gettext('BUTTON_SUBMIT') . '" />';
                $temp .= '</form>';
            } else {
                $this->error->isNullError();
                return null;
            }

            if (!is_null($formTitle)) {

                $formClass = $this->core->slugger($operator);

                $r = '<fieldset class="' . $formClass . '">';
                $r .= '<legend>';
                $r .= $formTitle;
                $r .= '</legend>';
                $r .= $temp;
                $r .= '</fieldset>';

                return $r;
            } else {
                return $temp;
            }

        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }


    /**
     * Végignyálazza a $_POST-ban található adatokat és mező típusnak megfelelően ellenőrzi a tartalmakat
     * Miután végzett kiüríti a $_POST-ot
     *
     * @return array
     */
    private function cleaner()
    {
        $rawData = $_POST;

        //var_dump($rawData);

        $returnArray = array();

        foreach ($rawData AS $rawKey => $data) {
            $dataTypeAndKey = explode('-', $rawKey);
            $dataType = $dataTypeAndKey[0];
            $dataKey = $dataTypeAndKey[1];

            if ($dataType != 'submit') {
                switch ($dataType) {
                    case 'textArea':
                        $returnArray[$dataType . '-' . $dataKey] = $this->core->cleanTextField($data);
                        break;
                    case 'num':
                        $returnArray[$dataType . '-' . $dataKey] = $this->keepNumbersOnly($this->core->cleanTextField($data));
                        break;
                    default:
                        $returnArray[$dataType . '-' . $dataKey] = $this->core->cleanVar($data);
                        break;
                }
            }
        }

        //var_dump($returnArray);

        $_POST = null;

        return $returnArray;

    }

    /**
     * A Validátor meghívása után a kérést továbbítja a tisztító metódusnak majd a visszakapott értékeketet értékei és vagy hibát ad vissza vagy az értéket
     * @return null|array
     */
    function validator()
    {
        $data = $this->cleaner();

        //var_dump($data);

        $returnArray = null;

        foreach ($data as $key => $value) {

            $dataTypeAndKey = explode('-', $key);
            $dataType = $dataTypeAndKey[0];
            $dataKey = $dataTypeAndKey[1];

            if ($dataType != 'submit') {
                switch ($dataType) {
                    case 'text':
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                    case 'textArea':
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                    case 'num':

                        break;
                    default:
                        if ($value != "") {
                            $returnArray[$dataKey] = $value;
                        } else {
                            $returnArray[$dataKey] = false;
                        }
                        break;
                }
            }
        }

        return $returnArray;
    }

    /**
     * Ha van a tömbben hibás (false) érték akkor igazolja, hogy van hiba a tömbben
     * @param null $dataArray
     * @return bool
     */
    public function checkForErrors($dataArray = null)
    {
        if (is_array($dataArray)) {

            if (in_array(false, $dataArray)) {
                return true;
            } else {
                return false;
            }

        } else {
            $this->error->isNullError();
            return true;
        }
    }

    /**
     * Csak a számokat tartja meg a bemeneti string-ből
     * @param $string
     * @return int
     */
    private function keepNumbersOnly($string)
    {
        $result = preg_replace("/[^0-9]/", "", $string);
        return $result;
    }
}
