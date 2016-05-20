CHANGELOG
=========

3.1.0
-----
 * deprecated passing objects as URI attributes to the ESI and SSI renderers
 * deprecated `ControllerResolver::getArguments()`
 * added `Makhan\Component\HttpKernel\Controller\ArgumentResolverInterface`
 * added `Makhan\Component\HttpKernel\Controller\ArgumentResolverInterface` as argument to `HttpKernel`
 * added `Makhan\Component\HttpKernel\Controller\ArgumentResolver`
 * added `Makhan\Component\HttpKernel\DataCollector\RequestDataCollector::getMethod()`
 * added `Makhan\Component\HttpKernel\DataCollector\RequestDataCollector::getRedirect()`
 * added the `kernel.controller_arguments` event, triggered after controller arguments have been resolved

3.0.0
-----

 * removed `Makhan\Component\HttpKernel\Kernel::init()`
 * removed `Makhan\Component\HttpKernel\Kernel::isClassInActiveBundle()` and `Makhan\Component\HttpKernel\KernelInterface::isClassInActiveBundle()`
 * removed `Makhan\Component\HttpKernel\Debug\TraceableEventDispatcher::setProfiler()`
 * removed `Makhan\Component\HttpKernel\EventListener\FragmentListener::getLocalIpAddresses()`
 * removed `Makhan\Component\HttpKernel\EventListener\LocaleListener::setRequest()`
 * removed `Makhan\Component\HttpKernel\EventListener\RouterListener::setRequest()`
 * removed `Makhan\Component\HttpKernel\EventListener\ProfilerListener::onKernelRequest()`
 * removed `Makhan\Component\HttpKernel\Fragment\FragmentHandler::setRequest()`
 * removed `Makhan\Component\HttpKernel\HttpCache\Esi::hasSurrogateEsiCapability()`
 * removed `Makhan\Component\HttpKernel\HttpCache\Esi::addSurrogateEsiCapability()`
 * removed `Makhan\Component\HttpKernel\HttpCache\Esi::needsEsiParsing()`
 * removed `Makhan\Component\HttpKernel\HttpCache\HttpCache::getEsi()`
 * removed `Makhan\Component\HttpKernel\DependencyInjection\ContainerAwareHttpKernel`
 * removed `Makhan\Component\HttpKernel\DependencyInjection\RegisterListenersPass`
 * removed `Makhan\Component\HttpKernel\EventListener\ErrorsLoggerListener`
 * removed `Makhan\Component\HttpKernel\EventListener\EsiListener`
 * removed `Makhan\Component\HttpKernel\HttpCache\EsiResponseCacheStrategy`
 * removed `Makhan\Component\HttpKernel\HttpCache\EsiResponseCacheStrategyInterface`
 * removed `Makhan\Component\HttpKernel\Log\LoggerInterface`
 * removed `Makhan\Component\HttpKernel\Log\NullLogger`
 * removed `Makhan\Component\HttpKernel\Profiler::import()`
 * removed `Makhan\Component\HttpKernel\Profiler::export()`

2.8.0
-----

 * deprecated `Profiler::import` and `Profiler::export`

2.7.0
-----

 * added the HTTP status code to profiles

2.6.0
-----

 * deprecated `Makhan\Component\HttpKernel\EventListener\ErrorsLoggerListener`, use `Makhan\Component\HttpKernel\EventListener\DebugHandlersListener` instead
 * deprecated unused method `Makhan\Component\HttpKernel\Kernel::isClassInActiveBundle` and `Makhan\Component\HttpKernel\KernelInterface::isClassInActiveBundle`

2.5.0
-----

 * deprecated `Makhan\Component\HttpKernel\DependencyInjection\RegisterListenersPass`, use `Makhan\Component\EventDispatcher\DependencyInjection\RegisterListenersPass` instead

2.4.0
-----

 * added event listeners for the session
 * added the KernelEvents::FINISH_REQUEST event

2.3.0
-----

 * [BC BREAK] renamed `Makhan\Component\HttpKernel\EventListener\DeprecationLoggerListener` to `Makhan\Component\HttpKernel\EventListener\ErrorsLoggerListener` and changed its constructor
 * deprecated `Makhan\Component\HttpKernel\Debug\ErrorHandler`, `Makhan\Component\HttpKernel\Debug\ExceptionHandler`,
   `Makhan\Component\HttpKernel\Exception\FatalErrorException` and `Makhan\Component\HttpKernel\Exception\FlattenException`
 * deprecated `Makhan\Component\HttpKernel\Kernel::init()`
 * added the possibility to specify an id an extra attributes to hinclude tags
 * added the collect of data if a controller is a Closure in the Request collector
 * pass exceptions from the ExceptionListener to the logger using the logging context to allow for more
   detailed messages

2.2.0
-----

 * [BC BREAK] the path info for sub-request is now always _fragment (or whatever you configured instead of the default)
 * added Makhan\Component\HttpKernel\EventListener\FragmentListener
 * added Makhan\Component\HttpKernel\UriSigner
 * added Makhan\Component\HttpKernel\FragmentRenderer and rendering strategies (in Makhan\Component\HttpKernel\Fragment\FragmentRendererInterface)
 * added Makhan\Component\HttpKernel\DependencyInjection\ContainerAwareHttpKernel
 * added ControllerReference to create reference of Controllers (used in the FragmentRenderer class)
 * [BC BREAK] renamed TimeDataCollector::getTotalTime() to
   TimeDataCollector::getDuration()
 * updated the MemoryDataCollector to include the memory used in the
   kernel.terminate event listeners
 * moved the Stopwatch classes to a new component
 * added TraceableControllerResolver
 * added TraceableEventDispatcher (removed ContainerAwareTraceableEventDispatcher)
 * added support for WinCache opcode cache in ConfigDataCollector

2.1.0
-----

 * [BC BREAK] the charset is now configured via the Kernel::getCharset() method
 * [BC BREAK] the current locale for the user is not stored anymore in the session
 * added the HTTP method to the profiler storage
 * updated all listeners to implement EventSubscriberInterface
 * added TimeDataCollector
 * added ContainerAwareTraceableEventDispatcher
 * moved TraceableEventDispatcherInterface to the EventDispatcher component
 * added RouterListener, LocaleListener, and StreamedResponseListener
 * added CacheClearerInterface (and ChainCacheClearer)
 * added a kernel.terminate event (via TerminableInterface and PostResponseEvent)
 * added a Stopwatch class
 * added WarmableInterface
 * improved extensibility between bundles
 * added profiler storages for Memcache(d), File-based, MongoDB, Redis
 * moved Filesystem class to its own component
