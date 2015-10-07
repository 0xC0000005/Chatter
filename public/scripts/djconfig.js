dojoConfig= {
    "async"	     : true,
    "parseOnLoad"    : true,
    "isDebug"        : true,
    "debugAtAllCosts": false,
    "locale"         : "en-GB",
    "cacheBust"      : new Date().getDate().toString(),
    "baseUrl"	     : "/scripts/",
	
	"packages"       : [
		{ name: "dojo", location: "lib/dojo" },
        { name: "dijit", location: "lib/dijit" },
        { name: "dojox", location: "lib/dojox" }
    ]
    
};
