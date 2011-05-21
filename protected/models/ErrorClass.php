<?php

/**
 * This is the model class for table "ErrorClass".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'ErrorClass':
 * @property integer $id
 * @property integer $compileLogEntryId
 * @property string $error
 *
 * The followings are the available model relations:
 * @property CompileLog $compileLogEntry
 */
class ErrorClass extends CActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @return ErrorClass the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'ErrorClass';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('compileLogEntryId', 'numerical', 'integerOnly'=>true),
			array('error', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, compileLogEntryId, error', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'compileLogEntry' => array(self::BELONGS_TO, 'CompileLogEntry', 'compileLogEntryId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'compileLogEntryId' => 'Compile Session',
			'error' => 'Error',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('compileLogEntryId',$this->compileLogEntryId);
		$criteria->compare('error',$this->error,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Gets the error class of the associated compilation log.
	 */
	public function assignClass($message) {
		if($message == '') return;

		$error_class = '';
		$msg_message = $message;
		//stripos expectations
		$pos_class_interface_expected = stripos($msg_message, "class, interface, or enum expected");
		$pos_invalid_method = stripos($msg_message, "invalid method declaration; return type required");
		$pos_unexpected_type = stripos($msg_message, "unexpected type");
		$pos_class_expected = stripos($msg_message, "'.class' expected");
		$pos_empty_character_literal = stripos($msg_message, "empty character literal");
		//stripos illegal sytax
		$pos_illegal_start = stripos($msg_message, "illegal start of expression");
		$pos_illegal_start_type = stripos($msg_message, "illegal start of type");
		$pos_illegal_esc = stripos($msg_message, "illegal escape character");
		$pos_unclosed_char_lit = stripos($msg_message, "unclosed character literal");
		//stripos statements
		$pos_not_statement = stripos($msg_message, "not a statement");
		$pos_unreachable = stripos($msg_message, "unreachable statement");
		$pos_missing_return = stripos($msg_message, "missing return statement");
		$pos_else_if = stripos($msg_message, "'else' without 'if'");
		$pos_method_void = stripos($msg_message, "cannot return a value from method whose result type is void");
		//stripos missing
		$pos_array_dim_missing = stripos($msg_message, "array dimension missing");
		$pos_unclosed_com = stripos($msg_message, "unclosed comment");
		$pos_missing_body = stripos($msg_message, "missing method body, or declare abstract");
		//stipos types
		$pos_inconvertible_types = stripos($msg_message, "inconvertible types");
		$pos_loss_precision = stripos($msg_message, "possible loss of precision");
		//stripos regular expression matches
		$pos_bracket = stripos($msg_message, "'(' expected");
		$pos_bracket2 = stripos($msg_message, "')' expected");
		$pos_bracket3 = stripos($msg_message, "'{' expected");
		$pos_bracket4 = stripos($msg_message, "'}' expected");
		$pos_bracket5 = stripos($msg_message, "'[' expected");
		$pos_bracket6 = stripos($msg_message, "']' expected");
		//stripos package resolution
		$pos_symbol = stripos($msg_message, "cannot find symbol");
		$pos_symbol_var = stripos($msg_message, "variable");
		$pos_symbol_method = stripos($msg_message, "method");
		$pos_symbol_class = stripos($msg_message, "class");
		$pos_symbol_constructor = stripos($msg_message, "constructor");
		$pos_package = stripos($msg_message, "package");
		$pos_package2 = stripos($msg_message, "does not exist");
		//stripos typing
		$pos_incompatible_types = stripos($msg_message, "incompatible types");
		$pos_incompatible_types2 = stripos($msg_message, "found");
		$pos_incompatible_types3 = stripos($msg_message, "but expected");
		$pos_type_mismatch = stripos($msg_message, "required, but");
		$pos_type_mismatch2 = stripos($msg_message, "found");
		$pos_illegal_char = stripos($msg_message, "illegal character");
		//stripos application
		$pos_op_application_error = stripos($msg_message, "operator");
		$pos_op_application_error2 = stripos($msg_message, "cannot be applied to");
		$pos_method_application_error = stripos($msg_message, "in");
		$pos_method_application_error2 = stripos($msg_message, "cannot be applied to");
		//stripos refernecing/dereferencing
		$pos_dereferencing_error = stripos($msg_message, "cannot be dereferenced");
		//stripos possible uninit variable
		$pos_uninit_var = stripos($msg_message, "variable");
		$pos_uninit_var2 = stripos($msg_message, "might not have been initialized");
		//stripos order of...
		$pos_prev_def = stripos($msg_message, "is already defined in");
		$pos_assign_final = stripos($msg_message, "cannot assign a value to final variable");
		//stripos access permissions
		$pos_access_violation = stripos($msg_message, "access in");
		//$pos_access_violation2 = stripos($msg_message, "private access in");
		$pos_method_reference = stripos($msg_message, "non-static method");//
		//$pos_method_reference2 = stripos($msg_message, "referenced from static content");//
		$pos_bad_modifier = stripos($msg_message, "illegal combination of modifiers");
		$pos_public_class = stripos($msg_message, "class");
		$pos_public_class2 = stripos($msg_message, "is public, should be");
		$pos_public_class3 = stripos($msg_message, "declared in file named");
		//file i/o
		$pos_file_io = stripos($msg_message, "error while writing");
		$pos_cannot_access = stripos($msg_message, "cannot access");
		$pos_void_type = stripos($msg_message, "'void' type not allowed here");
		$pos_incomparable_types = stripos($msg_message, "incomparable types");
		$pos_modifier_static = stripos($msg_message, "modifier static not allowed here");
		$pos_dot = stripos($msg_message, "'\\.' expected");
		$pos_super = stripos($msg_message, "call to super must be frist");
		$pos_super2 = stripos($msg_message, "statement in constructor");
		$pos_non_static_var = stripos($msg_message, "non-static variable");//
		//$pos_non_static_var2 = stripos($msg_message, "cannot be referenced from a static context");//
		$pos_weaker_privs = stripos($msg_message, "attempting to assign weaker access");
		$pos_weaker_privs2 = stripos($msg_message, "priveleges; was");
		$pos_no_instantiate = stripos($msg_message, "is abstract; cannot be instantiated");
		$pos_abs_no_override = stripos($msg_message, "is not abstract and does not");
		$pos_abs_no_override2 = stripos($msg_message, "override abstract method");
		$pos_cannot_override_ret = stripos($msg_message, "attempting to use incompatible return type");
		$pos_var_final = stripos($msg_message, "local variable");
		$pos_var_final2 = stripos($msg_message, "is accessed from within");
		$pos_var_final3 = stripos($msg_message, "inner class; needs to be declared final");
		$pos_catch_try = stripos($msg_message, "'catch' without 'try'");
		$pos_catch_without = stripos($msg_message, "'try' without 'catch' or 'finally'");
		$pos_unreported_exception = stripos($msg_message, "unreported exception");
		$pos_unreported_exception2 = stripos($msg_message, "caught of declared to be thrown");
		$pos_unthrown_exception = stripos($msg_message, "is never thrown in body");
		$pos_unthrown_exception2 = stripos($msg_message, "of corresponding try statement");
		$pos_break_outside = stripos($msg_message, "break outside switch or loop");
		$pos_while_expected = stripos($msg_message, "while expected");
		$pos_class_expected = stripos($msg_message, "class expected");
		$pos_missing_ret_val = stripos($msg_message, "missing return value");
		$pos_unclosed_string = stripos($msg_message, "unclosed string literal");
		$pos_not_enc_class = stripos($msg_message, "not an enclosing class:");
		$pos_colon_expected = stripos($msg_message, ": expected");
		$pos_orphaned_case = stripos($msg_message, "orphaned case");
		$pos_duplicate_lab = stripos($msg_message, "duplicate default label");
		$pos_ret_outside_method = stripos($msg_message, "return outside method");
		$pos_repeat_modifier = stripos($msg_message, "repeated modifier");
		$pos_ref_before = stripos($msg_message, "cannot reference");
		$pos_ref_before2 = stripos($msg_message, "before supertype");
		$pos_ref_before3 = stripos($msg_message, "constructor has been called");
		$pos_not_here = stripos($msg_message, "modifier");
		$pos_not_here2 = stripos($msg_message, "not allowed here");
		$pos_eq = stripos($msg_message, "= expected");
		$pos_no_int = stripos($msg_message, "no interface expected here");
		$pos_int = stripos($msg_message, "interface expected here");
		$pos_access_outside = stripos($msg_message, "cannot be accessed from outside package");
		$pos_illegal_fwd = stripos($msg_message, "illegal forward reference");
		$pos_abs_no_body = stripos($msg_message, "abstract methods cannot have a body");
		$pos_int_no_body = stripos($msg_message, "interface methods cannot have a body");
		$pos_exp_caught = stripos($msg_message, "exception");
		$pos_exp_caught2 = stripos($msg_message, "has already been caught");
		$pos_iden_exp = stripos($msg_message, "identifier");
		$pos_iden_exp2 = stripos($msg_message, "expected");
		$pos_unreachable_statement = stripos($msg_message, "unreachable statement");
		$pos_abs_not_reach = stripos($msg_message, "abstract method");
		$pos_abs_not_reach2 = stripos($msg_message, "cannot be accessed directly");
		$pos_illegal_init = stripos($msg_message, "illegal initializer for");
		$pos_int_large = stripos($msg_message, "integer number too large");
		$pos_semi_colon = stripos($msg_message, "';'");
		$pos_exp = stripos($msg_message, "expected");
		//find which one it is

		if ($pos_semi_colon !== false) {
			$error_class = 'semicolon';
		}
		elseif ($pos_class_interface_expected !== false) {
			$error_class = 'class-or-interface-expected';
		}
		elseif ($pos_invalid_method !== false) {
			$error_class = 'return-type-required';
		}
		elseif ($pos_unexpected_type !== false) {
			$error_class = 'unexpected-type';
		}
		elseif ($pos_class_expected !== false) {
			$error_class = '.class-expected';
		}
		elseif ($pos_empty_character_literal !== false) {
			$error_class = 'empty-character-literal';
		}
		elseif ($pos_illegal_start !== false) {
			$error_class = 'illegal-start-of-expression';
		}
		elseif ($pos_illegal_start_type !== false) {
			$error_class = 'illegal-start-of-type';
		}
		elseif ($pos_illegal_esc !== false) {
			$error_class = 'illegal-escape-character';
		}
		elseif ($pos_unclosed_char_lit !== false) {
			$error_class = 'unclosed-character-literal';
		}
		elseif ($pos_not_statement !== false) {
			$error_class = 'not-a-statement';
		}
		elseif ($pos_unreachable !== false) {
			$error_class = 'unreachable-statement';
		}
		elseif ($pos_missing_return !== false) {
			$error_class = 'missing-return';
		}
		elseif ($pos_else_if !== false) {
			$error_class = 'else-without-if';
		}
		elseif ($pos_method_void !== false) {
			$error_class = 'no-return-void-method';
		}
		elseif ($pos_array_dim_missing !== false) {
			$error_class = 'array-dim-missing';
		}
		elseif ($pos_unclosed_com !== false) {
			$error_class = 'unclosed-comment';
		}
		elseif ($pos_missing_body !== false) {
			$error_class = 'missing-body-or-abstract';
		}
		elseif ($pos_inconvertible_types !== false) {
			$error_class = 'inconvertible-types';
		}
		elseif ($pos_loss_precision !== false) {
			$error_class = 'loss-of-precision';
		}
		elseif ($pos_bracket !== false) {
			$error_class = 'parenthesis-expected';
		}
		elseif ($pos_bracket2 !== false) {
			$error_class = 'closing-parenthesis-expected';
		}
		elseif ($pos_bracket3 !== false) {
			$error_class = 'brace-expected';
		}
		elseif ($pos_bracket4 !== false) {
			$error_class = 'closing-brace-expected';
		}
		elseif ($pos_bracket5 !== false) {
			$error_class = 'bracket-expected';
		}
		elseif ($pos_bracket6 !== false) {
			$error_class = 'closing-bracket-expected';
		}
		elseif ($pos_symbol !== false) {
			if ($pos_symbol_var !== false) {
				$error_class = 'unknown-variable';
			}
			elseif ($pos_symbol_method !== false ){
				$error_class = 'unknown-method';
			}
			elseif ($pos_symbol_class !== false) {
				$error_class = 'unknown-class';
			}
			elseif ($pos_symbol_constructor !== false) {
				$error_class = 'unknown-constructor';
			}
			else {
				$error_class = 'unknown-symbol';
			}
		}
		elseif ($pos_package !== false && $pos_package2 !== false) {
			$error_class = 'package-not-exist';
		}
		elseif ($pos_incompatible_types !== false && $pos_incompatible_types2 !== false && $pos_incompatible_types3 !== false) {
			$error_class = 'incompatible-types';
		}
		elseif ($pos_type_mismatch !== false && $pos_type_mismatch2 !== false) {
			$error_class = 'type-mismatch';
		}
		elseif ($pos_illegal_char !== false) {
			$error_class = 'illegal-character';
		}
		elseif ($pos_op_application_error !== false && $pos_op_application_error2 !== false) {
			$error_class = 'op-application-error';
		}
		elseif ($pos_method_application_error !== false && $pos_method_application_error2 !== false) {
			$error_class = 'method-application-error';
		}
		elseif ($pos_dereferencing_error !== false) {
			$error_class = 'dereferencing-error';
		}
		elseif ($pos_uninit_var !== false && $pos_uninit_var2 !== false) {
			$error_class = 'possible-uninit-variable';
		}
		elseif ($pos_prev_def !== false) {
			$error_class = 'previously-defined-variable';
		}
		elseif ($pos_assign_final !== false) {
			$error_class = 'assign-to-final';
		}
		elseif ($pos_access_violation !== false) {
			$error_class = 'access-violation';
		}
		elseif ($pos_method_reference !== false) {
			$error_class = 'method-reference-from-static-contect';
		}
		elseif ($pos_bad_modifier !== false) {
			$error_class = 'bad-modifier-combination';
		}
		elseif ($pos_public_class !== false && $pos_public_class2 !== false && $pos_public_class3 !== false) {
			$error_class = 'class-public-in-file';
		}
		elseif ($pos_file_io !== false) {
			$error_class = 'file-io';
		}
		elseif ($pos_cannot_access !== false) {
			$error_class = 'cannot-access';
		}
		elseif ($pos_void_type !== false) {
			$error_class = 'void-type';
		}
		elseif ($pos_incomparable_types !== false) {
			$error_class = 'incomparable-types';
		}
		elseif ($pos_modifier_static !== false) {
			$error_class = 'modifier-static';
		}
		elseif ($pos_dot !== false) {
			$error_class = 'dot-expected';
		}
		elseif ($pos_super !== false && $pos_super2 !== false) {
			$error_class = 'super-first-statement';
		}
		elseif ($pos_non_static_var !== false) {
			$error_class = 'non-static-in-static-context';
		}
		elseif ($pos_weaker_privs !== false && $pos_weaker_privs2 !== false) {
			$error_class = 'weaker_privs';
		}
		elseif ($pos_no_instantiate !== false) {
			$error_class = 'abstract-no-instantiate';
		}
		elseif ($pos_abs_no_override !==false && $pos_abs_no_override2 !== false) {
			$error_class = 'abstract-no-override';
		}
		elseif ($pos_cannot_override_ret !== false) {
			$error_class = 'cannot-override-ret-type';
		}
		elseif ($pos_var_final !== false && $pos_var_final2 !== false && $pos_var_final3 !== false) {
			$error_class = 'declare-var-final';
		}
		elseif ($pos_catch_try !== false) {
			$error_class = 'catch-without-try';
		}
		elseif ($pos_catch_without !== false) {
			$error_class = 'try-without-catch';
		}
		elseif ($pos_unreported_exception !== false && $pos_unreported_exception2 !== false) {
			$error_class = 'unreported-exception';
		}
		elseif ($pos_unthrown_exception !== false && $pos_unthrown_exception2 !== false) {
			$error_class = 'unthrown-exception';
		}
		elseif ($pos_break_outside !== false) {
			$error_class = 'break-outside';
		}
		elseif ($pos_while_expected !== false) {
			$error_class = 'while-expected';
		}
		elseif ($pos_class_expected !== false) {
			$error_class = 'class-expected';
		}
		elseif ($pos_missing_ret_val !== false) {
			$error_class = 'missing-ret-val';
		}
		elseif ($pos_unclosed_string !== false) {
			$error_class = 'unclosed-string';
		}
		elseif ($pos_not_enc_class !== false) {
			$error_class = 'not-enc-class';
		}
		elseif ($pos_colon_expected !== false) {
			$error_class = 'colon-expected';
		}
		elseif ($pos_orphaned_case !== false) {
			$error_class = 'orphaned-case';
		}
		elseif ($pos_duplicate_lab !== false) {
			$error_class = 'duplicate-default-lab';
		}
		elseif ($pos_ret_outside_method !== false) {
			$error_class = 'ret-outside-method';
		}
		elseif ($pos_repeat_modifier !== false) {
			$error_class = 'repeated-modifier';
		}
		elseif ($pos_ref_before !== false && $pos_ref_before2 !== false && $pos_ref_before3 !== false) {
			$error_class = 'ref-before-supertype';
		}
		elseif ($pos_not_here !== false && $pos_not_here2 !== false) {
			$error_class = 'modifier-not-here';
		}
		elseif (stripos($msg_message, 'reached end of file') !== false) {
			$error_class = 'reached-eof';
		}
		elseif (stripos($msg_message, "'.class' expected") !== false) {
			$error_class = 'dot-class-expected';
		}
		elseif ($pos_eq !== false) {
			$error_class = 'eq-expected';
		}
		elseif ($pos_no_int !== false) {
			$error_class = 'no-interface-expected';
		}
		elseif ($pos_int !== false) {
			$error_class = 'interface-expected';
		}
		elseif ($pos_access_outside !== false) {
			$error_class = 'access-outside-pkg';
		}
		elseif ($pos_illegal_fwd !== false) {
			$error_class = 'illeg-fwd-reference';
		}
		elseif ($pos_abs_no_body !== false) {
			$error_class = 'abstract-no-body';
		}
		elseif ($pos_int_no_body !== false) {
			$error_class = 'interface-no-body';
		}
		elseif ($pos_exp_caught !== false && $pos_exp_caught2 !== false) {
			$error_class = 'exception-already-caught';
		}
		elseif (($pos_iden_exp !== false) && ($pos_iden_exp2 !== false)) {
			$error_class = 'identifier-expected';
		}
		elseif ($msg_message == "expected") {
			$error_class = 'unknown';
		}
		elseif ($pos_unreachable_statement !== false) {
			$error_class = 'unreachable-statement';
		}
		elseif ($pos_abs_not_reach !== false && $pos_abs_not_reach2 !== false) {
			$error_class = 'abstract-not-reachable';
		}
		elseif ($pos_illegal_init !== false) {
			$error_class = 'illegal-initiaizer';
		}
		elseif ($pos_int_large !== false) {
			$error_class = 'int-too-large';
		}
		else {
			$error_class = 'unknown';
		}
		$this->error = $error_class;
	}
}