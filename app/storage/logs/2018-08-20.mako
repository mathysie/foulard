[2018-08-20 16:07:20] mako.ERROR: Error: Class 'overhemd\datetime\OverhemdDateTime' not found in /home/mathijs/ict/overhemd/app/controllers/Tapschema.php:39
Stack trace:
#0 /home/mathijs/ict/overhemd/app/controllers/Tapschema.php(18): app\controllers\Tapschema->getStart(0)
#1 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/syringe/Container.php(570): app\controllers\Tapschema->getTapmail(0)
#2 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(285): mako\syringe\Container->call(Array, Array)
#3 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(321): mako\http\routing\Dispatcher->executeController(Object(app\controllers\Tapschema), Array)
#4 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(338): mako\http\routing\Dispatcher->executeAction(Object(mako\http\routing\Route))
#5 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(132): mako\http\routing\Dispatcher->mako\http\routing\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#6 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(218): mako\onion\Onion->mako\onion\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#7 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(339): mako\onion\Onion->peel(Object(Closure), Array)
#8 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/web/Application.php(48): mako\http\routing\Dispatcher->dispatch(Object(mako\http\routing\Route))
#9 /home/mathijs/ict/overhemd/public/index.php(15): mako\application\web\Application->run()
#10 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/cli/commands/server/router.php(32): require('/home/mathijs/i...')
#11 {main} {"exception":"[object] (Error(code: 0): Class 'overhemd\\datetime\\OverhemdDateTime' not found at /home/mathijs/ict/overhemd/app/controllers/Tapschema.php:39)"} 
[2018-08-20 16:08:33] mako.ERROR: RuntimeException: The [ overhemd ] config file does not exist. in /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/config/loaders/Loader.php:66
Stack trace:
#0 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/config/Config.php(102): mako\config\loaders\Loader->load('overhemd', NULL)
#1 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/config/Config.php(118): mako\config\Config->load('overhemd')
#2 /home/mathijs/ict/overhemd/app/controllers/Tapschema.php(55): mako\config\Config->get('overhemd.tapmai...')
#3 /home/mathijs/ict/overhemd/app/controllers/Tapschema.php(19): app\controllers\Tapschema->getEnd(Object(overhemd\datetime\OverhemdDateTime))
#4 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/syringe/Container.php(570): app\controllers\Tapschema->getTapmail(0)
#5 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(285): mako\syringe\Container->call(Array, Array)
#6 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(321): mako\http\routing\Dispatcher->executeController(Object(app\controllers\Tapschema), Array)
#7 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(338): mako\http\routing\Dispatcher->executeAction(Object(mako\http\routing\Route))
#8 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(132): mako\http\routing\Dispatcher->mako\http\routing\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#9 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(218): mako\onion\Onion->mako\onion\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#10 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(339): mako\onion\Onion->peel(Object(Closure), Array)
#11 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/web/Application.php(48): mako\http\routing\Dispatcher->dispatch(Object(mako\http\routing\Route))
#12 /home/mathijs/ict/overhemd/public/index.php(15): mako\application\web\Application->run()
#13 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/cli/commands/server/router.php(32): require('/home/mathijs/i...')
#14 {main} {"exception":"[object] (RuntimeException(code: 0): The [ overhemd ] config file does not exist. at /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/config/loaders/Loader.php:66)"} 
[2018-08-20 17:19:33] mako.ERROR: ErrorException: Undefined variable: succes in /home/mathijs/ict/overhemd/app/controllers/BaseController.php:46
Stack trace:
#0 /home/mathijs/ict/overhemd/app/controllers/BaseController.php(46): {closure}(8, 'Undefined varia...', '/home/mathijs/i...', 46, Array)
#1 /home/mathijs/ict/overhemd/app/controllers/Calendar.php(43): app\controllers\BaseController->getSuccess(Object(mako\view\View))
#2 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/syringe/Container.php(570): app\controllers\Calendar->bewerkAanvraag('5kugc2dkh6a6sga...')
#3 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(285): mako\syringe\Container->call(Array, Array)
#4 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(321): mako\http\routing\Dispatcher->executeController(Object(app\controllers\Calendar), Array)
#5 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(338): mako\http\routing\Dispatcher->executeAction(Object(mako\http\routing\Route))
#6 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(132): mako\http\routing\Dispatcher->mako\http\routing\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#7 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(218): mako\onion\Onion->mako\onion\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#8 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(339): mako\onion\Onion->peel(Object(Closure), Array)
#9 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/web/Application.php(48): mako\http\routing\Dispatcher->dispatch(Object(mako\http\routing\Route))
#10 /home/mathijs/ict/overhemd/public/index.php(15): mako\application\web\Application->run()
#11 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/cli/commands/server/router.php(32): require('/home/mathijs/i...')
#12 {main} {"exception":"[object] (ErrorException(code: 8): Undefined variable: succes at /home/mathijs/ict/overhemd/app/controllers/BaseController.php:46)"} 
[2018-08-20 17:21:11] mako.ERROR: Error: Call to a member function formatYMDTime() on null in /home/mathijs/ict/overhemd/overhemd/calendar/events/AanvraagEvent.php:120
Stack trace:
#0 /home/mathijs/ict/overhemd/app/controllers/Calendar.php(122): overhemd\calendar\events\AanvraagEvent->isValid(Object(mako\validator\ValidatorFactory), NULL)
#1 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/syringe/Container.php(570): app\controllers\Calendar->updateAanvraag('5kugc2dkh6a6sga...')
#2 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(285): mako\syringe\Container->call(Array, Array)
#3 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(321): mako\http\routing\Dispatcher->executeController(Object(app\controllers\Calendar), Array)
#4 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(338): mako\http\routing\Dispatcher->executeAction(Object(mako\http\routing\Route))
#5 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(132): mako\http\routing\Dispatcher->mako\http\routing\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#6 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(218): mako\onion\Onion->mako\onion\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#7 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(339): mako\onion\Onion->peel(Object(Closure), Array)
#8 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/web/Application.php(48): mako\http\routing\Dispatcher->dispatch(Object(mako\http\routing\Route))
#9 /home/mathijs/ict/overhemd/public/index.php(15): mako\application\web\Application->run()
#10 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/cli/commands/server/router.php(32): require('/home/mathijs/i...')
#11 {main} {"exception":"[object] (Error(code: 0): Call to a member function formatYMDTime() on null at /home/mathijs/ict/overhemd/overhemd/calendar/events/AanvraagEvent.php:120)"} 
[2018-08-20 17:30:01] mako.ERROR: ErrorException: touch(): Utime failed: Actie is niet toegestaan in /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_template_compiled.php:189
Stack trace:
#0 [internal function]: {closure}(2, 'touch(): Utime ...', '/home/mathijs/i...', 189, Array)
#1 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_template_compiled.php(189): touch('/home/mathijs/i...', 1534777962)
#2 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_template_compiled.php(141): Smarty_Template_Compiled->compileTemplateSource(Object(Smarty_Internal_Template))
#3 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_template_compiled.php(105): Smarty_Template_Compiled->process(Object(Smarty_Internal_Template))
#4 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_internal_template.php(206): Smarty_Template_Compiled->render(Object(Smarty_Internal_Template))
#5 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_internal_templatebase.php(232): Smarty_Internal_Template->render(false, 0)
#6 /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_internal_templatebase.php(116): Smarty_Internal_TemplateBase->_execute(Object(Smarty_Internal_Template), NULL, NULL, NULL, 0)
#7 /home/mathijs/ict/overhemd/vendor/bertptrs/marty/src/SmartyRenderer.php(31): Smarty_Internal_TemplateBase->fetch('/home/mathijs/i...')
#8 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/view/View.php(97): marty\SmartyRenderer->render('/home/mathijs/i...', Array)
#9 /home/mathijs/ict/overhemd/app/controllers/Calendar.php(45): mako\view\View->render()
#10 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/syringe/Container.php(570): app\controllers\Calendar->bewerkAanvraag('5kugc2dkh6a6sga...')
#11 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(285): mako\syringe\Container->call(Array, Array)
#12 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(321): mako\http\routing\Dispatcher->executeController(Object(app\controllers\Calendar), Array)
#13 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(338): mako\http\routing\Dispatcher->executeAction(Object(mako\http\routing\Route))
#14 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(132): mako\http\routing\Dispatcher->mako\http\routing\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#15 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/onion/Onion.php(218): mako\onion\Onion->mako\onion\{closure}(Object(mako\http\Request), Object(mako\http\Response))
#16 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/http/routing/Dispatcher.php(339): mako\onion\Onion->peel(Object(Closure), Array)
#17 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/web/Application.php(48): mako\http\routing\Dispatcher->dispatch(Object(mako\http\routing\Route))
#18 /home/mathijs/ict/overhemd/public/index.php(15): mako\application\web\Application->run()
#19 /home/mathijs/ict/overhemd/vendor/mako/framework/src/mako/application/cli/commands/server/router.php(32): require('/home/mathijs/i...')
#20 {main} {"exception":"[object] (ErrorException(code: 2): touch(): Utime failed: Actie is niet toegestaan at /home/mathijs/ict/overhemd/vendor/smarty/smarty/libs/sysplugins/smarty_template_compiled.php:189)"} 
