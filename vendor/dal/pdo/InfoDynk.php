<?php

/**
 *  Framework 
 *
 * @link      https://github.com/corner82/slim_test for the canonical source repository
 * @copyright Copyright (c) 2015  
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 * @since 16.07.2017
 */
class InfoDynk extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ info_dynk tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  16.07.2017
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();  
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                   $sql = " 
                UPDATE info_dynk
                SET  deleted= 1 , active = 1 ,
                     op_user_id = " . $opUserIdValue . "     
                WHERE id = " . intval($params['id']) ;
                $statement = $pdo->prepare( $sql);
              //   echo debugPDO($sql, $params);
                $update = $statement->execute();
                $afterRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_dynk tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  16.07.2017  
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $statement = $pdo->prepare("  
                SELECT 
                    a.id, 
                    a.question_id,
                    iq.question,  
                    a.deleted, 
                    sd.description as state_deleted,                 
                    a.active, 
                    sd1.description as state_active, 
                    a.op_user_id,
                    u.username AS op_user_name,
                    a.c_date  
                FROM info_dynk  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0    
                INNER JOIN info_users u ON u.id = a.op_user_id 
                INNER join info_questions iq on iq.id =   a.question_id                            
                ORDER BY   iq.question  
                                 ");
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_dynk tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  16.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $sql = "
                INSERT INTO info_dynk(  
                        question_id,  
                        subject_id,
                        op_user_id 
                       )
                VALUES ( 
                        " . intval($params['question_id']) . ", 
                        " . intval($params['subject_id']) . ",
                        " . intval($opUserIdValue) . "   
                            )   ";
                    $statement = $pdo->prepare($sql);
                    // echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('info_dynk_id_seq');
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'question';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_dynk tablosunda user_id li consultant daha önce kaydedilmiş mi ?  
     * @version v 1.0 16.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND a.id != " . intval($params['id']) . " ";
            }
            $sql = "  
            SELECT  
                a.question_id  AS question , 
                " . $params['question_id'] . "  AS value ,  
                question_id =  " . $params['question_id'] . " AS control,
                CONCAT(  ' Daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_dynk  a                          
            WHERE   
                subject_id =  " . $params['subject_id'] . "   and 
                question_id =  " . $params['question_id'] . "    
                  " . $addSql . " 
               AND a.deleted =0    
                               ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * info_dynk tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  16.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $sql = "
                    UPDATE info_dynk
                    SET   
                        subject_id = " . intval($params['subject_id']) . ",
                        question_id = " . intval($params['question_id']) . ",  
                        op_user_id = " . intval($opUserIdValue) . "   
                    WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    // echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $affectedRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                } else {
                    // 23505 	unique_violation
                    $errorInfo = '23505';
                    $errorInfoColumn = 'question_id';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için info_dynk tablosundan kayıtları döndürür !!
     * @version v 1.0  16.07.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
        if (isset($args['page']) && $args['page'] != "" && isset($args['rows']) && $args['rows'] != "") {
            $offset = ((intval($args['page']) - 1) * intval($args['rows']));
            $limit = intval($args['rows']);
        } else {
            $limit = 10;
            $offset = 0;
        }

        $sortArr = array();
        $orderArr = array();
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "  iq.question  ";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            //print_r($orderArr);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            //$order = "desc";
            $order = "ASC";
        }

        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
                SELECT 
                    a.id, 
                    a.question_id,
                    iq.question, 
                    
                   
                    a.deleted, 
                    sd.description as state_deleted,                 
                    a.active, 
                    sd1.description as state_active, 
                    a.op_user_id,
                    u.username AS op_user_name,
                    a.c_date  
                FROM info_dynk  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0    
                INNER JOIN info_users u ON u.id = a.op_user_id 
                INNER join info_questions iq on iq.id =   a.question_id                            
         
                WHERE a.deleted =0              
                ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
            $statement = $pdo->prepare($sql);
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            );
            //   echo debugPDO($sql, $parameters);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için info_dynk tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  16.07.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
               SELECT 
                    COUNT(a.id) AS COUNT  
                FROM info_dynk  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0    
                INNER JOIN info_users u ON u.id = a.op_user_id 
                INNER join info_questions iq on iq.id =   a.question_id  
                WHERE a.deleted =0                 
                    ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_dynk tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
     * @version v 1.0  16.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeActiveOrPassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                if (isset($params['id']) && $params['id'] != "") {

                    $sql = "                 
                UPDATE info_dynk
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_dynk
                                WHERE id = " . intval($params['id']) . "
                ),
                op_user_id = " . intval($opUserIdValue) . "
                WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $afterRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                }
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ tree doldurmak için sys_subjects tablosundan tüm kayıtları döndürür !!
     * @version v 1.0  16.07.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillQuestionOfExamTree($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $ExamId = 0;
            if (isset($params['exam_id']) && $params['exam_id'] != "") {
                $ExamId = $params['exam_id'];
            }
            $sql = "    
                SELECT                    
                    a.id, 
                    a.question_id, 
                    iq.question as name ,  
                    a.exam_id, 
                    a.active ,
                    'open' AS state_type,
                    'true'  AS root_type,
		    'true'   AS last_node,
                    NULL AS icon_class  
                FROM info_dynk a  
                inner join info_questions iq on iq.id= a.question_id and iq.active = 0 and iq.deleted =0
                WHERE                    
                   a.exam_id = " . intval($ExamId) . " AND 
                    a.deleted = 0  
                ORDER BY iq.question 
             
                                 ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ Tüm soru kayıtlarını döndürür !!
     * @version v 1.0  16.07.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillNotInQuestionOfExamLists($params = array()) {
        try {
            if (isset($params['page']) && $params['page'] != "" && isset($params['rows']) && $params['rows'] != "") {
                $offset = ((intval($params['page']) - 1) * intval($params['rows']));
                $limit = intval($params['rows']);
            } else {
                $limit = 10;
                $offset = 0;
            }

            $sortArr = array();
            $orderArr = array();
            $addSql = NULL;
            if (isset($params['sort']) && $params['sort'] != "") {
                $sort = trim($params['sort']);
                $sortArr = explode(",", $sort);
                if (count($sortArr) === 1)
                    $sort = trim($params['sort']);
            } else {
                $sort = "   a.question   ";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "DESC";
            }
            $sorguStr = null;
            if (isset($params['filterRules']) && $params['filterRules'] != "") {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'question':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.question" . $sorguExpression . ' ';

                                break;
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';

                                break;
                            case 'source':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.source" . $sorguExpression . ' ';

                                break;
                            case 'state_required_time':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd24.description" . $sorguExpression . ' ';

                                break;
                            case 'difficulty':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd23.description" . $sorguExpression . ' ';

                                break;

                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND ");

            $ExamId = 0;
            if (isset($params['exam_id']) && $params['exam_id'] != "") {
                $ExamId = $params['exam_id'];
            }
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            //     $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            //    if (\Utill\Dal\Helper::haveRecord($opUserId)) {

            $sql = " 
                SELECT 
                    a.id, 
                    a.question, 
                    a.description,  
                    a.source, 
                    a.point,  
                    a.difficulty_id, 
                    sd23.description as difficulty,      
                    a.required_time,
		    sd24.description as state_required_time ,  
                    a.active,  
                    a.op_user_id,
                    u.username AS op_user_name 
                FROM info_questions  a  
		INNER JOIN info_users u ON u.id = a.op_user_id 
		LEFT JOIN sys_specific_definitions sd23 ON sd23.main_group = 23 AND sd23.first_group= a.difficulty_id AND sd23.language_id = 647 AND sd23.deleted = 0 AND sd23.active = 0  
		LEFT JOIN sys_specific_definitions sd24 ON sd24.main_group = 24 AND sd24.first_group= a.required_time AND sd24.language_id = 647 AND sd24.deleted = 0 AND sd24.active = 0                           
                WHERE a.deleted =0 and 
                    a.id not in (
                        SELECT             
                            a.question_id  
                        FROM info_dynk a  
                        inner join info_questions iq on iq.id= a.question_id and iq.active = 0 and iq.deleted =0
                        WHERE                    
                           a.exam_id = " . intval($ExamId) . " AND 
                            a.deleted = 0   
                        ) 
                " . $addSql . "
                " . $sorguStr . " 
                ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
            $statement = $pdo->prepare($sql);
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            );
            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $parameters);                
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            $affectedRows = $statement->rowCount();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            /* } else {
              $errorInfo = '23502';   // 23502  user_id not_null_violation
              $errorInfoColumn = 'pk';
              return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
              }
             * 
             */
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN
     * @ Tüm soru kayıtlarının sayısını döndürür !!
     * @version v 1.0  16.07.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillNotInQuestionOfExamListsRtc($params = array()) {
        try {
            $addSql = NULL;
            $sorguStr = null;
            if (isset($params['filterRules']) && $params['filterRules'] != "") {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'question':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.question" . $sorguExpression . ' ';

                                break;
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';

                                break;
                            case 'source':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.source" . $sorguExpression . ' ';

                                break;
                            case 'state_required_time':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd24.description" . $sorguExpression . ' ';

                                break;
                            case 'difficulty':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd23.description" . $sorguExpression . ' ';

                                break;
                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND ");

            $ExamId = 0;
            if (isset($params['exam_id']) && $params['exam_id'] != "") {
                $ExamId = $params['exam_id'];
            }

            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            //   $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            //  if (\Utill\Dal\Helper::haveRecord($opUserId)) { 
            $sql = "
                    SELECT 
                       COUNT(a.id) AS COUNT                    
                    FROM info_questions  a  
		INNER JOIN info_users u ON u.id = a.op_user_id 
		LEFT JOIN sys_specific_definitions sd23 ON sd23.main_group = 23 AND sd23.first_group= a.difficulty_id AND sd23.language_id = 647 AND sd23.deleted = 0 AND sd23.active = 0  
		LEFT JOIN sys_specific_definitions sd24 ON sd24.main_group = 24 AND sd24.first_group= a.required_time AND sd24.language_id = 647 AND sd24.deleted = 0 AND sd24.active = 0                           
                WHERE a.deleted =0 and 
                    a.id not in (
                        SELECT             
                            a.question_id  
                        FROM info_dynk a  
                        inner join info_questions iq on iq.id= a.question_id and iq.active = 0 and iq.deleted =0
                        WHERE                    
                           a.exam_id = " . intval($ExamId) . " AND 
                            a.deleted = 0   
                        ) 
                        " . $addSql . "
                        " . $sorguStr . " 
                        ";
            $statement = $pdo->prepare($sql);

            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $parameters);                
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            $affectedRows = $statement->rowCount();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            /* } else {
              $errorInfo = '23502';   // 23502  user_id not_null_violation
              $errorInfoColumn = 'pk';
              return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
              }
             * 
             */
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
        /**
     * @author Okan CIRAN
     * @ info_dynk tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  16.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function transferPropertyMachineGroup($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "  
                INSERT INTO info_dynk(                        
                         question_id,
                         subject_id,
                         op_user_id
                         ) VALUES (
                        " . intval($params['question_id']) . ",
                        " . intval($params['subject_id']) . ",                        
                        " . intval($opUserIdValue) . ")  
                                 ";
                    $statement = $pdo->prepare($sql);
                   //  echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('info_dynk_id_seq');
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'subject_id';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }


}
