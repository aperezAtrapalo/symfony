/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

#ifndef PHP_SYMFONY_DEBUG_H
#define PHP_SYMFONY_DEBUG_H

extern zend_module_entry makhan_debug_module_entry;
#define phpext_makhan_debug_ptr &makhan_debug_module_entry

#define PHP_SYMFONY_DEBUG_VERSION "2.7"

#ifdef PHP_WIN32
#	define PHP_SYMFONY_DEBUG_API __declspec(dllexport)
#elif defined(__GNUC__) && __GNUC__ >= 4
#	define PHP_SYMFONY_DEBUG_API __attribute__ ((visibility("default")))
#else
#	define PHP_SYMFONY_DEBUG_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

ZEND_BEGIN_MODULE_GLOBALS(makhan_debug)
	intptr_t req_rand_init;
	void (*old_error_cb)(int type, const char *error_filename, const uint error_lineno, const char *format, va_list args);
	zval *debug_bt;
ZEND_END_MODULE_GLOBALS(makhan_debug)

PHP_MINIT_FUNCTION(makhan_debug);
PHP_MSHUTDOWN_FUNCTION(makhan_debug);
PHP_RINIT_FUNCTION(makhan_debug);
PHP_RSHUTDOWN_FUNCTION(makhan_debug);
PHP_MINFO_FUNCTION(makhan_debug);
PHP_GINIT_FUNCTION(makhan_debug);
PHP_GSHUTDOWN_FUNCTION(makhan_debug);

PHP_FUNCTION(makhan_zval_info);
PHP_FUNCTION(makhan_debug_backtrace);

static char *_makhan_debug_memory_address_hash(void * TSRMLS_DC);
static const char *_makhan_debug_zval_type(zval *);
static const char* _makhan_debug_get_resource_type(long TSRMLS_DC);
static int _makhan_debug_get_resource_refcount(long TSRMLS_DC);

void makhan_debug_error_cb(int type, const char *error_filename, const uint error_lineno, const char *format, va_list args);

#ifdef ZTS
#define SYMFONY_DEBUG_G(v) TSRMG(makhan_debug_globals_id, zend_makhan_debug_globals *, v)
#else
#define SYMFONY_DEBUG_G(v) (makhan_debug_globals.v)
#endif

#endif	/* PHP_SYMFONY_DEBUG_H */
