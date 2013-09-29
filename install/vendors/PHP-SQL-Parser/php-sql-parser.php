<?php

/**
 * php-sql-parser.php
 *
 * A pure PHP SQL (non validating) parser w/ focus on MySQL dialect of SQL
 *
 * Copyright (c) 2010-2012, Justin Swanhart
 * with contributions by André Rothe <arothe@phosco.info, phosco@gmx.de>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
 * BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

if (!defined('HAVE_PHP_SQL_PARSER')) {

    require_once(dirname(__FILE__) . '/classes/parser-utils.php');
    require_once(dirname(__FILE__) . '/classes/lexer.php');
    require_once(dirname(__FILE__) . '/classes/position-calculator.php');

    /**
     * This class implements the parser functionality.
     * @author greenlion@gmail.com
     * @author arothe@phosco.info
     */
    class PHPSQLParser extends PHPSQLParserUtils {

        private $lexer;

        public function __construct($sql = false, $calcPositions = false) {
            $this->lexer = new PHPSQLLexer();
            if ($sql) {
                $this->parse($sql, $calcPositions);
            }
        }

        public function parse($sql, $calcPositions = false) {
            #lex the SQL statement
            $inputArray = $this->split_sql($sql);

            #This is the highest level lexical analysis.  This is the part of the
            #code which finds UNION and UNION ALL query parts
            $queries = $this->processUnion($inputArray);

            # If there was no UNION or UNION ALL in the query, then the query is
            # stored at $queries[0].
            if (!$this->isUnion($queries)) {
                $queries = $this->processSQL($queries[0]);
            }

            # calc the positions of some important tokens
            if ($calcPositions) {
                $calculator = new PositionCalculator();
                $queries = $calculator->setPositionsWithinSQL($sql, $queries);
            }

            # store the parsed queries
            $this->parsed = $queries;
            return $this->parsed;
        }

        private function processUnion($inputArray) {
            $outputArray = array();

            #sometimes the parser needs to skip ahead until a particular
            #token is found
            $skipUntilToken = false;

            #This is the last type of union used (UNION or UNION ALL)
            #indicates a) presence of at least one union in this query
            #          b) the type of union if this is the first or last query
            $unionType = false;

            #Sometimes a "query" consists of more than one query (like a UNION query)
            #this array holds all the queries
            $queries = array();

            foreach ($inputArray as $key => $token) {
                $trim = trim($token);

                # overread all tokens till that given token
                if ($skipUntilToken) {
                    if ($trim === "") {
                        continue; # read the next token
                    }
                    if (strtoupper($trim) === $skipUntilToken) {
                        $skipUntilToken = false;
                        continue; # read the next token
                    }
                }

                if (strtoupper($trim) !== "UNION") {
                    $outputArray[] = $token; # here we get empty tokens, if we remove these, we get problems in parse_sql()
                    continue;
                }

                $unionType = "UNION";

                # we are looking for an ALL token right after UNION
                for ($i = $key + 1; $i < count($inputArray); ++$i) {
                    if (trim($inputArray[$i]) === "") {
                        continue;
                    }
                    if (strtoupper($inputArray[$i]) !== "ALL") {
                        break;
                    }
                    # the other for-loop should overread till "ALL"
                    $skipUntilToken = "ALL";
                    $unionType = "UNION ALL";
                }

                # store the tokens related to the unionType
                $queries[$unionType][] = $outputArray;
                $outputArray = array();
            }

            # the query tokens after the last UNION or UNION ALL
            # or we don't have an UNION/UNION ALL
            if (!empty($outputArray)) {
                if ($unionType) {
                    $queries[$unionType][] = $outputArray;
                } else {
                    $queries[] = $outputArray;
                }
            }

            return $this->processMySQLUnion($queries);
        }

        /** MySQL supports a special form of UNION:
         * (select ...)
         * union
         * (select ...)
         *
         * This function handles this query syntax.  Only one such subquery
         * is supported in each UNION block.  (select)(select)union(select) is not legal.
         * The extra queries will be silently ignored.
         */
        private function processMySQLUnion($queries) {
            $unionTypes = array('UNION', 'UNION ALL');
            foreach ($unionTypes as $unionType) {

                if (empty($queries[$unionType])) {
                    continue;
                }

                foreach ($queries[$unionType] as $key => $tokenList) {
                    foreach ($tokenList as $z => $token) {
                        $token = trim($token);
                        if ($token === "") {
                            continue;
                        }

                        # starts with "(select"
                        if (preg_match("/^\\(\\s*select\\s*/i", $token)) {
                            $queries[$unionType][$key] = $this->parse($this->removeParenthesisFromStart($token));
                            break;
                        }

                        $queries[$unionType][$key] = $this->processSQL($queries[$unionType][$key]);
                        break;
                    }
                }
            }
            # it can be parsed or not
            return $queries;
        }

        private function isUnion($queries) {
            $unionTypes = array('UNION', 'UNION ALL');
            foreach ($unionTypes as $unionType) {
                if (!empty($queries[$unionType])) {
                    return true;
                }
            }
            return false;
        }

        #this function splits up a SQL statement into easy to "parse"
        #tokens for the SQL processor
        private function split_sql($sql) {
            return $this->lexer->split($sql);
        }

        /* This function breaks up the SQL statement into logical sections.
         Some sections are then further handled by specialized functions.
         */
        private function processSQL(&$tokens) {
            $prev_category = "";
            $token_category = "";
            $skip_next = false;
            $out = false;

            $tokenCount = count($tokens);
            for ($tokenNumber = 0; $tokenNumber < $tokenCount; ++$tokenNumber) {

                $token = $tokens[$tokenNumber];
                $trim = trim($token); # this removes also \n and \t!

                # if it starts with an "(", it should follow a SELECT
                if ($trim !== "" && $trim[0] == "(" && $token_category == "") {
                    $token_category = 'SELECT';
                }

                /* If it isn't obvious, when $skip_next is set, then we ignore the next real
                 token, that is we ignore whitespace.
                 */
                if ($skip_next) {
                    if ($trim === "") {
                        if ($token_category !== "") { # is this correct??
                            $out[$token_category][] = $token;
                        }
                        continue;
                    }
                    #to skip the token we replace it with whitespace
                    $trim = "";
                    $token = "";
                    $skip_next = false;
                }

                $upper = strtoupper($trim);
                switch ($upper) {

                /* Tokens that get their own sections. These keywords have subclauses. */
                case 'SELECT':
                case 'ORDER':
                case 'LIMIT':
                case 'SET':
                case 'DUPLICATE':
                case 'VALUES':
                case 'GROUP':
                case 'ORDER':
                case 'HAVING':
                case 'WHERE':
                case 'RENAME':
                case 'CALL':
                case 'PROCEDURE':
                case 'FUNCTION':
                case 'DATABASE':
                case 'SERVER':
                case 'LOGFILE':
                case 'DEFINER':
                case 'RETURNS':
                case 'TABLESPACE':
                case 'TRIGGER':
                case 'DO':
                case 'PLUGIN':
                case 'FROM':
                case 'FLUSH':
                case 'KILL':
                case 'RESET':
                case 'START':
                case 'STOP':
                case 'PURGE':
                case 'EXECUTE':
                case 'PREPARE':
                case 'DEALLOCATE':
                    if ($trim === 'DEALLOCATE') {
                        $skip_next = true;
                    }
                    /* this FROM is different from FROM in other DML (not join related) */
                    if ($token_category === 'PREPARE' && $upper === 'FROM') {
                        continue 2;
                    }

                    $token_category = $upper;
                    break;

                case 'EVENT':
                # issue 71
                    if ($prev_category === 'DROP' || $prev_category === 'ALTER' || $prev_category === 'CREATE') {
                        $token_category = $upper;
                    }
                    break;

                case 'DATA':
                # prevent wrong handling of DATA as keyword
                    if ($prev_category === 'LOAD') {
                        $token_category = $upper;
                    }
                    break;

                case 'PASSWORD':
                # prevent wrong handling of PASSWORD as keyword
                    if ($prev_category === 'SET') {
                        $token_category = $upper;
                    }
                    break;

                case 'INTO':
                # prevent wrong handling of CACHE within LOAD INDEX INTO CACHE...
                    if ($prev_category === 'LOAD') {
                        $out[$prev_category][] = $upper;
                        continue 2;
                    }
                    $token_category = $upper;
                    break;

                case 'USER':
                # prevent wrong processing as keyword
                    if ($prev_category === 'CREATE' || $prev_category === 'RENAME' || $prev_category === 'DROP') {
                        $token_category = $upper;
                    }
                    break;

                case 'VIEW':
                # prevent wrong processing as keyword
                    if ($prev_category === 'CREATE' || $prev_category === 'ALTER' || $prev_category === 'DROP') {
                        $token_category = $upper;
                    }
                    break;

                /* These tokens get their own section, but have no subclauses.
                 These tokens identify the statement but have no specific subclauses of their own. */
                case 'DELETE':
                case 'ALTER':
                case 'INSERT':
                case 'REPLACE':
                case 'TRUNCATE':
                case 'CREATE':
                case 'TRUNCATE':
                case 'OPTIMIZE':
                case 'GRANT':
                case 'REVOKE':
                case 'SHOW':
                case 'HANDLER':
                case 'LOAD':
                case 'ROLLBACK':
                case 'SAVEPOINT':
                case 'UNLOCK':
                case 'INSTALL':
                case 'UNINSTALL':
                case 'ANALZYE':
                case 'BACKUP':
                case 'CHECK':
                case 'CHECKSUM':
                case 'REPAIR':
                case 'RESTORE':
                case 'DESCRIBE':
                case 'EXPLAIN':
                case 'USE':
                case 'HELP':
                    $token_category = $upper; /* set the category in case these get subclauses
                                              in a future version of MySQL */
                    $out[$upper][0] = $upper;
                    continue 2;
                    break;

                case 'CACHE':
                    if ($prev_category === "" || $prev_category === 'RESET' || $prev_category === 'FLUSH'
                            || $prev_category === 'LOAD') {
                        $token_category = $upper;
                        continue 2;
                    }
                    break;

                /* This is either LOCK TABLES or SELECT ... LOCK IN SHARE MODE*/
                case 'LOCK':
                    if ($token_category === "") {
                        $token_category = $upper;
                        $out[$upper][0] = $upper;
                    } else {
                        $trim = 'LOCK IN SHARE MODE';
                        $skip_next = true;
                        $out['OPTIONS'][] = $trim;
                    }
                    continue 2;
                    break;

                case 'USING': /* USING in FROM clause is different from USING w/ prepared statement*/
                    if ($token_category === 'EXECUTE') {
                        $token_category = $upper;
                        continue 2;
                    }
                    if ($token_category === 'FROM' && !empty($out['DELETE'])) {
                        $token_category = $upper;
                        continue 2;
                    }
                    break;

                /* DROP TABLE is different from ALTER TABLE DROP ... */
                case 'DROP':
                    if ($token_category !== 'ALTER') {
                        $token_category = $upper;
                        $out[$upper][0] = $upper;
                        continue 2;
                    }
                    break;

                case 'FOR':
                    $skip_next = true;
                    $out['OPTIONS'][] = 'FOR UPDATE';
                    continue 2;
                    break;

                case 'UPDATE':
                    if ($token_category === "") {
                        $token_category = $upper;
                        continue 2;

                    }
                    if ($token_category === 'DUPLICATE') {
                        continue 2;
                    }
                    break;

                case 'START':
                    $trim = "BEGIN";
                    $out[$upper][0] = $upper;
                    $skip_next = true;
                    break;

                /* These tokens are ignored. */
                case 'BY':
                case 'ALL':
                case 'SHARE':
                case 'MODE':
                case 'TO':
                case ';':
                    continue 2;
                    break;

                case 'KEY':
                    if ($token_category === 'DUPLICATE') {
                        continue 2;
                    }
                    break;

                /* These tokens set particular options for the statement.  They never stand alone.*/
                case 'DISTINCTROW':
                    $trim = 'DISTINCT';
                case 'DISTINCT':
                case 'HIGH_PRIORITY':
                case 'LOW_PRIORITY':
                case 'DELAYED':
                case 'IGNORE':
                case 'FORCE':
                case 'STRAIGHT_JOIN':
                case 'SQL_SMALL_RESULT':
                case 'SQL_BIG_RESULT':
                case 'QUICK':
                case 'SQL_BUFFER_RESULT':
                case 'SQL_CACHE':
                case 'SQL_NO_CACHE':
                case 'SQL_CALC_FOUND_ROWS':
                    $out['OPTIONS'][] = $upper;
                    continue 2;
                    break;

                case 'WITH':
                    if ($token_category === 'GROUP') {
                        $skip_next = true;
                        $out['OPTIONS'][] = 'WITH ROLLUP';
                        continue 2;
                    }
                    break;

                case 'AS':
                    break;

                case '':
                case ',':
                case ';':
                    break;

                default:
                    break;
                }

                # remove obsolete category after union (empty category because of
                # empty token before select)
                if ($token_category !== "" && ($prev_category === $token_category)) {
                    $out[$token_category][] = $token;
                }

                $prev_category = $token_category;
            }

            return $this->processSQLParts($out);
        }

        private function processSQLParts($out) {
            if (!$out) {
                return false;
            }
            if (!empty($out['SELECT'])) {
                $out['SELECT'] = $this->process_select($out['SELECT']);
            }
            if (!empty($out['FROM'])) {
                $out['FROM'] = $this->process_from($out['FROM']);
            }
            if (!empty($out['USING'])) {
                $out['USING'] = $this->process_from($out['USING']);
            }
            if (!empty($out['UPDATE'])) {
                $out['UPDATE'] = $this->process_from($out['UPDATE']);
            }
            if (!empty($out['GROUP'])) {
                # set empty array if we have partial SQL statement 
                $out['GROUP'] = $this->process_group($out['GROUP'], isset($out['SELECT']) ? $out['SELECT'] : array());
            }
            if (!empty($out['ORDER'])) {
                # set empty array if we have partial SQL statement
                $out['ORDER'] = $this->process_order($out['ORDER'], isset($out['SELECT']) ? $out['SELECT'] : array());
            }
            if (!empty($out['LIMIT'])) {
                $out['LIMIT'] = $this->process_limit($out['LIMIT']);
            }
            if (!empty($out['WHERE'])) {
                $out['WHERE'] = $this->process_expr_list($out['WHERE']);
            }
            if (!empty($out['HAVING'])) {
                $out['HAVING'] = $this->process_expr_list($out['HAVING']);
            }
            if (!empty($out['SET'])) {
                $out['SET'] = $this->process_set_list($out['SET'], isset($out['UPDATE']));
            }
            if (!empty($out['DUPLICATE'])) {
                $out['ON DUPLICATE KEY UPDATE'] = $this->process_set_list($out['DUPLICATE']);
                unset($out['DUPLICATE']);
            }
            if (!empty($out['INSERT'])) {
                $out = $this->process_insert($out);
            }
            if (!empty($out['REPLACE'])) {
                $out = $this->process_insert($out, 'REPLACE');
            }
            if (!empty($out['DELETE'])) {
                $out = $this->process_delete($out);
            }
            if (!empty($out['VALUES'])) {
                $out = $this->process_values($out);
            }
            if (!empty($out['INTO'])) {
                $out = $this->process_into($out);
            }
            return $out;
        }

        /* A SET list is simply a list of key = value expressions separated by comma (,).
         This function produces a list of the key/value expressions.
         */
        private function getAssignment($base_expr) {
            $assignment = $this->process_expr_list($this->split_sql($base_expr));
            return array('expr_type' => 'expression', 'base_expr' => trim($base_expr), 'sub_tree' => $assignment);
        }

        private function getVariableType($expression) {
            // $expression must contain only upper-case characters
            if ($expression[1] !== "@") {
                return 'user_variable';
            }

            $type = substr($expression, 2, strpos($expression, ".", 2));

            switch ($type) {
            case 'GLOBAL':
            case 'LOCAL':
            case 'SESSION':
                $type = strtolower($type) . '_variable';
                break;
            default:
                $type = 'session_variable';
                break;
            }
            return $type;
        }

        private function process_set_list($tokens, $isUpdate) {
            $result = array();
            $baseExpr = "";
            $assignment = false;
            $varType = false;

            foreach ($tokens as $token) {
                $upper = strtoupper(trim($token));

                switch ($upper) {
                case 'LOCAL':
                case 'SESSION':
                case 'GLOBAL':
                    if (!$isUpdate) {
                        $varType = strtolower($upper) . '_variable';
                        $baseExpr = "";
                        continue 2;
                    }
                    break;

                case ',':
                    $assignment = $this->getAssignment($baseExpr);
                    if (!$isUpdate) {
                        if ($varType !== false) {
                            $assignment['sub_tree'][0]['expr_type'] = $varType;
                        }
                    }
                    $result[] = $assignment;
                    $baseExpr = "";
                    $varType = false;
                    continue 2;

                default:
                }
                $baseExpr .= $token;
            }

            if (trim($baseExpr) !== "") {
                $assignment = $this->getAssignment($baseExpr);
                if (!$isUpdate) {
                    if ($varType !== false) {
                        $assignment['sub_tree'][0]['expr_type'] = $varType;
                    }
                }
                $result[] = $assignment;
            }

            return $result;
        }

        /* This function processes the LIMIT section.
         start,end are set.  If only end is provided in the query
         then start is set to 0.
         */
        private function process_limit($tokens) {
            $rowcount = "";
            $offset = "";

            $comma = -1;
            $exchange = false;

            for ($i = 0; $i < count($tokens); ++$i) {
                $trim = trim($tokens[$i]);
                if ($trim === ",") {
                    $comma = $i;
                    break;
                }
                if ($trim === "OFFSET") {
                    $comma = $i;
                    $exchange = true;
                    break;
                }
            }

            for ($i = 0; $i < $comma; ++$i) {
                if ($exchange) {
                    $rowcount .= $tokens[$i];
                } else {
                    $offset .= $tokens[$i];
                }
            }

            for ($i = $comma + 1; $i < count($tokens); ++$i) {
                if ($exchange) {
                    $offset .= $tokens[$i];
                } else {
                    $rowcount .= $tokens[$i];
                }
            }

            return array('offset' => trim($offset), 'rowcount' => trim($rowcount));
        }

        /* This function processes the SELECT section.  It splits the clauses at the commas.
         Each clause is then processed by process_select_expr() and the results are added to
         the expression list.
        
         Finally, at the end, the epxression list is returned.
         */
        private function process_select(&$tokens) {
            $expression = "";
            $expr = array();
            foreach ($tokens as $token) {
                if (trim($token) === ',') {
                    $expr[] = $this->process_select_expr(trim($expression));
                    $expression = "";
                } else {
                    $expression .= $token;
                }
            }
            if ($expression) {
                $expr[] = $this->process_select_expr(trim($expression));
            }
            return $expr;
        }

        private function revokeEscaping($sql) {
            $sql = trim($sql);
            if (($sql[0] === '`') && ($sql[strlen($sql) - 1] === '`')) {
                $sql = substr($sql, 1, -1);
            }
            return str_replace('``', '`', $sql);
        }

        private function isWhitespaceToken($token) {
            return (trim($token) === "");
        }

        private function isCommentToken($token) {
            return isset($token[0]) && isset($token[1])
                    && (($token[0] === '-' && $token[1] === '-') || ($token[0] === '/' && $token[1] === '*'));
        }

        private function isColumnReference($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'colref');
        }

        private function isReserved($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'reserved');
        }

        private function isConstant($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'const');
        }

        private function isAggregateFunction($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'aggregate_function');
        }

        private function isFunction($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'function');
        }

        private function isExpression($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'expression');
        }

        private function isBrackedExpression($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'bracked_expression');
        }

        private function isSubQuery($out) {
            return (isset($out['expr_type']) && $out['expr_type'] === 'subquery');
        }

        /* This fuction processes each SELECT clause.  We determine what (if any) alias
         is provided, and we set the type of expression.
         */
        private function process_select_expr($expression) {

            $tokens = $this->split_sql($expression);
            $token_count = count($tokens);

            /* Determine if there is an explicit alias after the AS clause.
             If AS is found, then the next non-whitespace token is captured as the alias.
             The tokens after (and including) the AS are removed.
             */
            $base_expr = "";
            $stripped = array();
            $capture = false;
            $alias = false;
            $processed = false;

            for ($i = 0; $i < $token_count; ++$i) {
                $token = $tokens[$i];
                $upper = strtoupper($token);

                if ($upper === 'AS') {
                    $alias = array('as' => true, "name" => "", "base_expr" => $token);
                    $tokens[$i] = "";
                    $capture = true;
                    continue;
                }

                if (!$this->isWhitespaceToken($upper)) {
                    $stripped[] = $token;
                }

                // we have an explicit AS, next one can be the alias
                // but also a comment!
                if ($capture) {
                    if (!$this->isWhitespaceToken($upper) && !$this->isCommentToken($upper)) {
                        $alias['name'] .= $token;
                        array_pop($stripped);
                    }
                    $alias['base_expr'] .= $token;
                    $tokens[$i] = "";
                    continue;
                }

                $base_expr .= $token;
            }

            $stripped = $this->process_expr_list($stripped);

            # TODO: the last part can also be a comment, don't use array_pop

            # we remove the last token, if it is a colref,
            # it can be an alias without an AS
            $last = array_pop($stripped);
            if (!$alias && $this->isColumnReference($last)) {

                # TODO: it can be a comment, don't use array_pop

                # check the token before the colref
                $prev = array_pop($stripped);

                if ($this->isReserved($prev) || $this->isConstant($prev) || $this->isAggregateFunction($prev)
                        || $this->isFunction($prev) || $this->isExpression($prev) || $this->isSubQuery($prev)
                        || $this->isColumnReference($prev) || $this->isBrackedExpression($prev)) {

                    $alias = array('as' => false, 'name' => trim($last['base_expr']),
                                   'base_expr' => trim($last['base_expr']));
                    #remove the last token
                    array_pop($tokens);
                    $base_expr = join("", $tokens);
                }
            }

            if (!$alias) {
                $base_expr = join("", $tokens);
            } else {
                /* remove escape from the alias */
                $alias['name'] = $this->revokeEscaping(trim($alias['name']));
                $alias['base_expr'] = trim($alias['base_expr']);
            }

            # this is always done with $stripped, how we do it twice?
            $processed = $this->process_expr_list($tokens);

            # if there is only one part, we copy the expr_type
            # in all other cases we use "expression" as global type
            $type = 'expression';
            if (count($processed) == 1) {
                if (!$this->isSubQuery($processed[0])) {
                    $type = $processed[0]['expr_type'];
                    $base_expr = $processed[0]['base_expr'];
                    $processed = $processed[0]['sub_tree']; // it can be FALSE
                }
            }

            return array('expr_type' => $type, 'alias' => $alias, 'base_expr' => trim($base_expr),
                         'sub_tree' => $processed);
        }

        private function process_from(&$tokens) {

            $parseInfo = $this->initParseInfoForFrom();
            $expr = array();

            $skip_next = false;
            $i = 0;

            foreach ($tokens as $token) {
                $upper = strtoupper(trim($token));

                if ($skip_next && $token !== "") {
                    $parseInfo['token_count']++;
                    $skip_next = false;
                    continue;
                } else {
                    if ($skip_next) {
                        continue;
                    }
                }

                switch ($upper) {
                case 'OUTER':
                case 'LEFT':
                case 'RIGHT':
                case 'NATURAL':
                case 'CROSS':
                case ',':
                case 'JOIN':
                case 'INNER':
                    break;

                default:
                    $parseInfo['expression'] .= $token;
                    if ($parseInfo['ref_type'] !== false) { # all after ON / USING
                        $parseInfo['ref_expr'] .= $token;
                    }
                    break;
                }

                switch ($upper) {
                case 'AS':
                    $parseInfo['alias'] = array('as' => true, 'name' => "", 'base_expr' => $token);
                    $parseInfo['token_count']++;
                    $n = 1;
                    $str = "";
                    while ($str == "") {
                        $parseInfo['alias']['base_expr'] .= ($tokens[$i + $n] === "" ? " " : $tokens[$i + $n]);
                        $str = trim($tokens[$i + $n]);
                        ++$n;
                    }
                    $parseInfo['alias']['name'] = $str;
                    $parseInfo['alias']['base_expr'] = trim($parseInfo['alias']['base_expr']);
                    continue;

                case 'INDEX':
                    if ($token_category == 'CREATE') {
                        $token_category = $upper;
                        continue 2;
                    }

                    break;

                case 'USING':
                case 'ON':
                    $parseInfo['ref_type'] = $upper;
                    $parseInfo['ref_expr'] = "";

                case 'CROSS':
                case 'USE':
                case 'FORCE':
                case 'IGNORE':
                case 'INNER':
                case 'OUTER':
                    $parseInfo['token_count']++;
                    continue;
                    break;

                case 'FOR':
                    $parseInfo['token_count']++;
                    $skip_next = true;
                    continue;
                    break;

                case 'LEFT':
                case 'RIGHT':
                case 'STRAIGHT_JOIN':
                    $parseInfo['next_join_type'] = $upper;
                    break;

                case ',':
                    $parseInfo['next_join_type'] = 'CROSS';

                case 'JOIN':
                    if ($parseInfo['subquery']) {
                        $parseInfo['sub_tree'] = $this->parse($this->removeParenthesisFromStart($parseInfo['subquery']));
                        $parseInfo['expression'] = $parseInfo['subquery'];
                    }

                    $expr[] = $this->processFromExpression($parseInfo);
                    $parseInfo = $this->initParseInfoForFrom($parseInfo);
                    break;

                default:
                    if ($upper === "") {
                        continue; # ends the switch statement!
                    }

                    if ($parseInfo['token_count'] === 0) {
                        if ($parseInfo['table'] === "") {
                            $parseInfo['table'] = $token;
                        }
                    } else if ($parseInfo['token_count'] === 1) {
                        $parseInfo['alias'] = array('as' => false, 'name' => trim($token), 'base_expr' => trim($token));
                    }
                    $parseInfo['token_count']++;
                    break;
                }
                ++$i;
            }

            $expr[] = $this->processFromExpression($parseInfo);
            return $expr;
        }

        private function initParseInfoForFrom($parseInfo = false) {
            # first init
            if ($parseInfo === false) {
                $parseInfo = array('join_type' => "", 'saved_join_type' => "JOIN");
            }
            # loop init
            return array('expression' => "", 'token_count' => 0, 'table' => "", 'alias' => false, 'join_type' => "",
                         'next_join_type' => "", 'saved_join_type' => $parseInfo['saved_join_type'],
                         'ref_type' => false, 'ref_expr' => false, 'base_expr' => false, 'sub_tree' => false,
                         'subquery' => "");
        }

        private function processFromExpression(&$parseInfo) {

            $res = array();

            # exchange the join types (join_type is save now, saved_join_type holds the next one)
            $parseInfo['join_type'] = $parseInfo['saved_join_type']; # initialized with JOIN
            $parseInfo['saved_join_type'] = ($parseInfo['next_join_type'] ? $parseInfo['next_join_type'] : 'JOIN');

            # we have a reg_expr, so we have to parse it
            if ($parseInfo['ref_expr'] !== false) {
                $unparsed = $this->split_sql($this->removeParenthesisFromStart($parseInfo['ref_expr']));

                // here we can get a comma separated list
                foreach ($unparsed as $k => $v) {
                    if (trim($v) === ',') {
                        $unparsed[$k] = "";
                    }
                }
                $parseInfo['ref_expr'] = $this->process_expr_list($unparsed);
            }

            # there is an expression, we have to parse it
            if (substr(trim($parseInfo['table']), 0, 1) == '(') {
                $parseInfo['expression'] = $this->removeParenthesisFromStart($parseInfo['table']);

                if (preg_match("/^\\s*select/i", $parseInfo['expression'])) {
                    $parseInfo['sub_tree'] = $this->parse($parseInfo['expression']);
                    $res['expr_type'] = 'subquery';
                } else {
                    $tmp = $this->split_sql($parseInfo['expression']);
                    $parseInfo['sub_tree'] = $this->process_from($tmp);
                    $res['expr_type'] = 'table_expression';
                }
            } else {
                $res['expr_type'] = 'table';
                $res['table'] = $parseInfo['table'];
            }

            $res['alias'] = $parseInfo['alias'];
            $res['join_type'] = $parseInfo['join_type'];
            $res['ref_type'] = $parseInfo['ref_type'];
            $res['ref_clause'] = $parseInfo['ref_expr'];
            $res['base_expr'] = trim($parseInfo['expression']);
            $res['sub_tree'] = $parseInfo['sub_tree'];
            return $res;
        }

        private function processOrderExpression(&$parseInfo, $select) {
            $parseInfo['expr'] = trim($parseInfo['expr']);

            if ($parseInfo['expr'] === "") {
                return false;
            }

            $parseInfo['expr'] = trim($this->revokeEscaping($parseInfo['expr']));

            if (is_numeric($parseInfo['expr'])) {
                $parseInfo['type'] = 'pos';
            } else {
                #search to see if the expression matches an alias
                foreach ($select as $clause) {
                    if (!$clause['alias']) {
                        continue;
                    }
                    if ($clause['alias']['name'] === $parseInfo['expr']) {
                        $parseInfo['type'] = 'alias';
                    }
                }
            }

            if ($parseInfo['type'] === "expression") {
                $expr = $this->process_select_expr($parseInfo['expr']);
                $expr['direction'] = $parseInfo['dir'];
                unset($expr['alias']);
                return $expr;
            }

            return array('expr_type' => $parseInfo['type'], 'base_expr' => $parseInfo['expr'],
                         'direction' => $parseInfo['dir']);
        }

        private function initParseInfoForOrder() {
            return array('expr' => "", 'dir' => "ASC", 'type' => 'expression');
        }

        private function process_order($tokens, $select) {
            $out = array();
            $parseInfo = $this->initParseInfoForOrder();

            if (!$tokens) {
                return false;
            }

            foreach ($tokens as $token) {
                $upper = strtoupper(trim($token));
                switch ($upper) {
                case ',':
                    $out[] = $this->processOrderExpression($parseInfo, $select);
                    $parseInfo = $this->initParseInfoForOrder();
                    break;

                case 'DESC':
                    $parseInfo['dir'] = "DESC";
                    break;

                case 'ASC':
                    $parseInfo['dir'] = "ASC";
                    break;

                default:
                    $parseInfo['expr'] .= $token;

                }
            }

            $out[] = $this->processOrderExpression($parseInfo, $select);
            return $out;
        }

        private function process_group($tokens, $select) {
            $out = array();
            $parseInfo = $this->initParseInfoForOrder();

            if (!$tokens) {
                return false;
            }

            foreach ($tokens as $token) {
                $trim = strtoupper(trim($token));
                switch ($trim) {
                case ',':
                    $parsed = $this->processOrderExpression($parseInfo, $select);
                    unset($parsed['direction']);

                    $out[] = $parsed;
                    $parseInfo = $this->initParseInfoForOrder();
                    break;
                default:
                    $parseInfo['expr'] .= $token;

                }
            }

            $parsed = $this->processOrderExpression($parseInfo, $select);
            unset($parsed['direction']);
            $out[] = $parsed;

            return $out;
        }

        private function removeParenthesisFromStart($token) {

            $parenthesisRemoved = 0;

            $trim = trim($token);
            if ($trim !== "" && $trim[0] === "(") { // remove only one parenthesis pair now!
                $parenthesisRemoved++;
                $trim[0] = " ";
                $trim = trim($trim);
            }

            $parenthesis = $parenthesisRemoved;
            $i = 0;
            $string = 0;
            while ($i < strlen($trim)) {

                if ($trim[$i] === "\\") {
                    $i += 2; # an escape character, the next character is irrelevant
                    continue;
                }

                if ($trim[$i] === "'" || $trim[$i] === '"') {
                    $string++;
                }

                if (($string % 2 === 0) && ($trim[$i] === "(")) {
                    $parenthesis++;
                }

                if (($string % 2 === 0) && ($trim[$i] === ")")) {
                    if ($parenthesis == $parenthesisRemoved) {
                        $trim[$i] = " ";
                        $parenthesisRemoved--;
                    }
                    $parenthesis--;
                }
                $i++;
            }
            return trim($trim);
        }

        private function initParseInfoExprList($parseInfo = false) {
            if ($parseInfo === false) {
                return array('processed' => false, 'expr' => "", 'key' => false, 'token' => false, 'tokenType' => "",
                             'prevToken' => "", 'prevTokenType' => "", 'trim' => false, 'upper' => false);
            }

            $expr = $parseInfo['expr'];
            $expr[] = array('expr_type' => $parseInfo['tokenType'], 'base_expr' => $parseInfo['token'],
                            'sub_tree' => $parseInfo['processed']);

            return array('processed' => false, 'expr' => $expr, 'key' => false, 'token' => false, 'tokenType' => "",
                         'prevToken' => $parseInfo['upper'], 'prevTokenType' => $parseInfo['tokenType'],
                         'trim' => false, 'upper' => false);
        }

        /* Some sections are just lists of expressions, like the WHERE and HAVING clauses.  This function
         processes these sections.  Recursive.
         */
        private function process_expr_list($tokens) {

            $parseInfo = $this->initParseInfoExprList();
            $skip_next = false;

            foreach ($tokens as $parseInfo['key'] => $parseInfo['token']) {

                $parseInfo['trim'] = trim($parseInfo['token']);

                if ($parseInfo['trim'] === "") {
                    continue;
                }

                if ($skip_next) {
                    # skip the next non-whitespace token
                    $skip_next = false;
                    continue;
                }

                $parseInfo['upper'] = strtoupper($parseInfo['trim']);

                /* is it a subquery?*/
                if (preg_match("/^\\(\\s*SELECT/i", $parseInfo['trim'])) {
                    #tokenize and parse the subquery.
                    #we remove the enclosing parenthesis for the tokenizer
                    $parseInfo['processed'] = $this->parse($this->removeParenthesisFromStart($parseInfo['trim']));
                    $parseInfo['tokenType'] = 'subquery';

                } elseif ($parseInfo['upper'][0] === '(' && substr($parseInfo['upper'], -1) === ')') {
                    /* is it an inlist (upper is derived from trim!) */

                    # if we have a colref followed by a parenthesis pair,
                    # it isn't a colref, it is a user-function
                    if ($parseInfo['prevTokenType'] === 'colref' || $parseInfo['prevTokenType'] === 'function'
                            || $parseInfo['prevTokenType'] === 'aggregate_function') {

                        $tmptokens = $this->split_sql($this->removeParenthesisFromStart($parseInfo['trim']));
                        foreach ($tmptokens as $k => $v) {
                            if (trim($v) == ',') {
                                unset($tmptokens[$k]);
                            }
                        }

                        $tmptokens = array_values($tmptokens);
                        $parseInfo['processed'] = $this->process_expr_list($tmptokens);

                        $last = array_pop($parseInfo['expr']);
                        $parseInfo['token'] = $last['base_expr'];
                        $parseInfo['tokenType'] = ($parseInfo['prevTokenType'] === 'colref' ? 'function'
                                : $parseInfo['prevTokenType']);
                        $parseInfo['prevTokenType'] = $parseInfo['prevToken'] = "";
                    }

                    if ($parseInfo['prevToken'] == 'IN') {

                        $tmptokens = $this->split_sql($this->removeParenthesisFromStart($parseInfo['trim']));
                        foreach ($tmptokens as $k => $v) {
                            if (trim($v) == ',') {
                                unset($tmptokens[$k]);
                            }
                        }

                        $tmptokens = array_values($tmptokens);
                        $parseInfo['processed'] = $this->process_expr_list($tmptokens);
                        $parseInfo['prevTokenType'] = $parseInfo['prevToken'] = "";
                        $parseInfo['tokenType'] = "in-list";
                    }

                    if ($parseInfo['prevToken'] == 'AGAINST') {

                        $tmptokens = $this->split_sql($this->removeParenthesisFromStart($parseInfo['trim']));
                        if (count($tmptokens) > 1) {
                            $match_mode = implode('', array_slice($tmptokens, 1));
                            $parseInfo['processed'] = array($list[0], $match_mode);
                        } else {
                            $parseInfo['processed'] = $list[0];
                        }

                        $parseInfo['prevTokenType'] = $parseInfo['prevToken'] = "";
                        $parseInfo['tokenType'] = "match-arguments";
                    }

                } elseif ($parseInfo['upper'][0] === '@') {
                    // a variable
                    $parseInfo['tokenType'] = $this->getVariableType($parseInfo['upper']);
                    $parseInfo['processed'] = false;
                } else {
                    /* it is either an operator, a colref or a constant */
                    switch ($parseInfo['upper']) {

                    case '*':
                        $parseInfo['processed'] = false; #no subtree

                        # last token is colref, const or expression
                        # it is an operator, in all other cases it is an all-columns-alias
                        # if the previous colref ends with a dot, the * is the all-columns-alias
                        if (!is_array($parseInfo['expr'])) {
                            $parseInfo['tokenType'] = "colref"; # single or first element of select -> *
                            break;
                        }

                        $last = array_pop($parseInfo['expr']);
                        if ($last['expr_type'] !== 'colref' && $last['expr_type'] !== 'const'
                                && $last['expr_type'] !== 'expression') {
                            $parseInfo['expr'][] = $last;
                            $parseInfo['tokenType'] = "colref";
                            break;
                        }

                        if ($last['expr_type'] === 'colref' && substr($last['base_expr'], -1, 1) === ".") {
                            $last['base_expr'] .= '*'; # tablealias dot *
                            $parseInfo['expr'][] = $last;
                            continue 2;
                        }

                        $parseInfo['expr'][] = $last;
                        $parseInfo['tokenType'] = "operator";
                        break;

                    case 'AND':
                    case '&&':
                    case 'BETWEEN':
                    case 'AND':
                    case 'BINARY':
                    case '&':
                    case '~':
                    case '|':
                    case '^':
                    case 'DIV':
                    case '/':
                    case '<=>':
                    case '=':
                    case '>=':
                    case '>':
                    case 'IS':
                    case 'NOT':
                    case '<<':
                    case '<=':
                    case '<':
                    case 'LIKE':
                    case '%':
                    case '!=':
                    case '<>':
                    case 'REGEXP':
                    case '!':
                    case '||':
                    case 'OR':
                    case '>>':
                    case 'RLIKE':
                    case 'SOUNDS':
                    case 'XOR':
                    case 'IN':
                        $parseInfo['processed'] = false;
                        $parseInfo['tokenType'] = "operator";
                        break;

                    case 'NULL':
                        $parseInfo['processed'] = false;
                        $parseInfo['tokenType'] = 'const';
                        break;

                    case '-':
                    case '+':
                    // differ between preceding sign and operator
                        $parseInfo['processed'] = false;

                        if ($parseInfo['prevTokenType'] === 'colref' || $parseInfo['prevTokenType'] === 'function'
                                || $parseInfo['prevTokenType'] === 'aggregate_function'
                                || $parseInfo['prevTokenType'] === 'const'
                                || $parseInfo['prevTokenType'] === 'subquery') {
                            $parseInfo['tokenType'] = "operator";
                        } else {
                            $parseInfo['tokenType'] = "sign";
                        }
                        break;

                    default:
                        switch ($parseInfo['token'][0]) {
                        case "'":
                        case '"':
                            $parseInfo['tokenType'] = 'const';
                            break;
                        case '`':
                            $parseInfo['tokenType'] = 'colref';
                            break;

                        default:
                            if (is_numeric($parseInfo['token'])) {
                                $parseInfo['tokenType'] = 'const';

                                if ($parseInfo['prevTokenType'] === 'sign') {
                                    array_pop($parseInfo['expr']);
                                    $parseInfo['token'] = $parseInfo['prevToken'] . $parseInfo['token'];
                                }

                            } else {
                                $parseInfo['tokenType'] = 'colref';
                            }
                            break;

                        }
                        $parseInfo['processed'] = false;
                    }
                }

                /* is a reserved word? */
                if ($parseInfo['tokenType'] !== 'operator' && $parseInfo['tokenType'] !== 'in-list'
                        && $parseInfo['tokenType'] !== 'function' && $parseInfo['tokenType'] !== 'aggregate_function'
                        && in_array($parseInfo['upper'], parent::$reserved)) {

                    switch ($parseInfo['upper']) {
                    case 'AVG':
                    case 'SUM':
                    case 'COUNT':
                    case 'MIN':
                    case 'MAX':
                    case 'STDDEV':
                    case 'STDDEV_SAMP':
                    case 'STDDEV_POP':
                    case 'VARIANCE':
                    case 'VAR_SAMP':
                    case 'VAR_POP':
                    case 'GROUP_CONCAT':
                    case 'BIT_AND':
                    case 'BIT_OR':
                    case 'BIT_XOR':
                        $parseInfo['tokenType'] = 'aggregate_function';
                        break;

                    case 'NULL':
                    // it is a reserved word, but we would like to have set it as constant
                        $parseInfo['tokenType'] = 'const';
                        break;

                    default:
                        if (in_array($parseInfo['upper'], parent::$functions)) {
                            $parseInfo['tokenType'] = 'function';
                        } else {
                            $parseInfo['tokenType'] = 'reserved';
                        }
                        break;
                    }
                }

                if (!$parseInfo['tokenType']) {
                    if ($parseInfo['upper'][0] === '(') {
                        $local_expr = $this->removeParenthesisFromStart($parseInfo['trim']);
                        $parseInfo['tokenType'] = 'bracket_expression';
                    } else {
                        $local_expr = $parseInfo['trim'];
                        $parseInfo['tokenType'] = 'expression';
                    }
                    $parseInfo['processed'] = $this->process_expr_list($this->split_sql($local_expr));
                }

                $parseInfo = $this->initParseInfoExprList($parseInfo);
            } // end of for-loop

            return (is_array($parseInfo['expr']) ? $parseInfo['expr'] : false);
        }

        private function process_update($tokens) {

        }

        private function process_delete($tokens) {
            $tables = array();
            $del = $tokens['DELETE'];

            foreach ($tokens['DELETE'] as $expression) {
                if ($expression != 'DELETE' && trim($expression, ' .*') != "" && $expression != ',') {
                    $tables[] = trim($expression, '.* ');
                }
            }

            if (empty($tables)) {
                foreach ($tokens['FROM'] as $table) {
                    $tables[] = $table['table'];
                }
            }

            $tokens['DELETE'] = array('TABLES' => $tables);
            return $tokens;
        }

        private function process_insert($tokens, $token_category = 'INSERT') {
            $table = "";
            $cols = array();

            $into = $tokens['INTO'];
            foreach ($into as $token) {
                if (trim($token) === "")
                    continue;
                if ($table === "") {
                    $table = $token;
                } elseif (empty($cols)) {
                    $cols[] = $token;
                }
            }

            if (empty($cols)) {
                $cols = false;
            } else {
                $columns = explode(",", $this->removeParenthesisFromStart($cols[0]));
                $cols = array();
                foreach ($columns as $k => $v) {
                    $cols[] = array('expr_type' => 'colref', 'base_expr' => trim($v));
                }
            }

            unset($tokens['INTO']);
            $tokens[$token_category] = array('table' => $table, 'columns' => $cols, 'base_expr' => $table);
            return $tokens;
        }

        private function process_record($unparsed) {

            $unparsed = $this->removeParenthesisFromStart($unparsed);
            $values = $this->split_sql($unparsed);

            foreach ($values as $k => $v) {
                if (trim($v) === ",") {
                    $values[$k] = "";
                }
            }
            return $this->process_expr_list($values);
        }

        private function process_values($tokens) {

            $unparsed = "";
            foreach ($tokens['VALUES'] as $k => $v) {
                if (trim($v) === "") {
                    continue;
                }
                $unparsed .= $v;
            }

            $values = $this->split_sql($unparsed);

            $parsed = array();
            foreach ($values as $k => $v) {
                if (trim($v) === ",") {
                    unset($values[$k]);
                } else {
                    $values[$k] = array('expr_type' => 'record', 'base_expr' => $v, 'data' => $this->process_record($v));
                }
            }

            $tokens['VALUES'] = array_values($values);
            return $tokens;
        }

        /**
         * TODO: This is a dummy function, we cannot parse INTO as part of SELECT
         * at the moment
         */
        private function process_into($tokens) {
            $unparsed = $tokens['INTO'];
            foreach ($unparsed as $k => $token) {
                if ((trim($token) === "") || (trim($token) === ",")) {
                    unset($unparsed[$k]);
                }
            }
            $tokens['INTO'] = array_values($unparsed);
            return $tokens;
        }
    }
    define('HAVE_PHP_SQL_PARSER', 1);
}
