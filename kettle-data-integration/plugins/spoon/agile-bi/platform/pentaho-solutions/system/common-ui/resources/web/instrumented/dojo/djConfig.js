/* automatically generated by JSCoverage - do not edit */
try {
  if (typeof top === 'object' && top !== null && typeof top.opener === 'object' && top.opener !== null) {
    // this is a browser window that was opened from another window

    if (! top.opener._$jscoverage) {
      top.opener._$jscoverage = {};
    }
  }
}
catch (e) {}

try {
  if (typeof top === 'object' && top !== null) {
    // this is a browser window

    try {
      if (typeof top.opener === 'object' && top.opener !== null && top.opener._$jscoverage) {
        top._$jscoverage = top.opener._$jscoverage;
      }
    }
    catch (e) {}

    if (! top._$jscoverage) {
      top._$jscoverage = {};
    }
  }
}
catch (e) {}

try {
  if (typeof top === 'object' && top !== null && top._$jscoverage) {
    _$jscoverage = top._$jscoverage;
  }
}
catch (e) {}
if (typeof _$jscoverage !== 'object') {
  _$jscoverage = {};
}
if (! _$jscoverage['dojo/djConfig.js']) {
  _$jscoverage['dojo/djConfig.js'] = [];
  _$jscoverage['dojo/djConfig.js'][34] = 0;
  _$jscoverage['dojo/djConfig.js'][35] = 0;
  _$jscoverage['dojo/djConfig.js'][39] = 0;
  _$jscoverage['dojo/djConfig.js'][40] = 0;
}
_$jscoverage['dojo/djConfig.js'].source = ["<span class=\"c\">/*</span>","<span class=\"c\"> defaults for djConfig (dojo). to make use of the defaults</span>","<span class=\"c\"> simple extend it rather than redefine it. This will result in the</span>","<span class=\"c\"> combination of the two (must come after webcontext.js include):</span>","","<span class=\"c\">  &lt;script type=\"text/javascript\" src=\"webcontext.js?content=common-ui\"&gt;&lt;/script&gt;</span>","<span class=\"c\">  &lt;script type=\"text/javascript\"&gt;</span>","","<span class=\"c\">    $.extend(djConfig,</span>","<span class=\"c\">        { modulePaths: {</span>","<span class=\"c\">            'pentaho.common': \"../pentaho/common\"</span>","<span class=\"c\">        },</span>","<span class=\"c\">        parseOnLoad: true,</span>","<span class=\"c\">        baseUrl: '../dojo/dojo/'</span>","<span class=\"c\">    });</span>","","<span class=\"c\">  &lt;/script&gt;</span>","","","","<span class=\"c\">  *if you want to completely ignore the defaults, just define the djConfig var like normal</span>","<span class=\"c\">    &lt;script type=\"text/javascript\"&gt;</span>","<span class=\"c\">      var djConfig = { modulePaths: {</span>","<span class=\"c\">              'pentaho.common': \"../pentaho/common\"</span>","<span class=\"c\">          },</span>","<span class=\"c\">          parseOnLoad: true,</span>","<span class=\"c\">          baseUrl: '../dojo/dojo/'</span>","<span class=\"c\">      });</span>","<span class=\"c\">    &lt;/script&gt;</span>","<span class=\"c\"> */</span>","","","<span class=\"c\">// don't overwrite this if they've set djConfig ahead of time</span>","<span class=\"k\">if</span><span class=\"k\">(</span>djConfig <span class=\"k\">==</span> <span class=\"s\">'undefined'</span> <span class=\"k\">||</span> djConfig <span class=\"k\">==</span> undefined<span class=\"k\">)</span> <span class=\"k\">{</span>","  <span class=\"k\">var</span> djConfig <span class=\"k\">=</span> <span class=\"k\">{</span>","    disableFlashStorage<span class=\"k\">:</span> <span class=\"k\">true</span> <span class=\"c\">/* turn off flash storage for client-side caching */</span>","  <span class=\"k\">}</span><span class=\"k\">;</span>","<span class=\"k\">}</span> <span class=\"k\">else</span> <span class=\"k\">{</span>","  <span class=\"k\">if</span><span class=\"k\">(</span>djConfig<span class=\"k\">[</span><span class=\"s\">'disableFlashStorage'</span><span class=\"k\">]</span> <span class=\"k\">==</span> <span class=\"s\">'undefined'</span> <span class=\"k\">||</span> djConfig<span class=\"k\">[</span><span class=\"s\">'disableFlashStorage'</span><span class=\"k\">]</span> <span class=\"k\">==</span> undefined<span class=\"k\">)</span> <span class=\"k\">{</span>","    djConfig<span class=\"k\">.</span>disableFlashStorage <span class=\"k\">=</span> <span class=\"k\">true</span><span class=\"k\">;</span>","  <span class=\"k\">}</span>","<span class=\"k\">}</span>"];
_$jscoverage['dojo/djConfig.js'][34]++;
if (((djConfig == "undefined") || (djConfig == undefined))) {
  _$jscoverage['dojo/djConfig.js'][35]++;
  var djConfig = {disableFlashStorage: true};
}
else {
  _$jscoverage['dojo/djConfig.js'][39]++;
  if (((djConfig.disableFlashStorage == "undefined") || (djConfig.disableFlashStorage == undefined))) {
    _$jscoverage['dojo/djConfig.js'][40]++;
    djConfig.disableFlashStorage = true;
  }
}