import PriceHistoryPlugin from "./price-history-plugin/price-history-plugin.plugin";

const PluginManager = window.PluginManager;
PluginManager.register('PriceHistoryPlugin', PriceHistoryPlugin, '[data-price-history-plugin]')