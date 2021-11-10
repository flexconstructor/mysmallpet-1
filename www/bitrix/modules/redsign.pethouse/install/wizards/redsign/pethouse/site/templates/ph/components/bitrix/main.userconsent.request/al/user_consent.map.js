{"version":3,"file":"user_consent.min.js","sources":["user_consent.js"],"names":["UserConsentControl","params","this","caller","formNode","controlNode","inputNode","config","prototype","BX","UserConsent","msg","title","btnAccept","btnReject","loading","errTextLoad","events","save","refused","accepted","textList","current","autoSave","isFormSubmitted","isConsentSaved","attributeControl","load","context","item","find","bind","loadAll","limit","forEach","loadFromForms","formNodes","document","getElementsByTagName","convert","nodeListToArray","controlNodes","querySelectorAll","map","createItem","filter","submitEventName","addCustomEvent","onSubmit","onClick","querySelector","JSON","parse","getAttribute","parameters","tagName","findParent","e","requestForItem","preventDefault","check","checked","saveConsent","setCurrent","requestConsent","id","sec","replace","onAccepted","onRefused","actionRequestUrl","actionUrl","onCustomEvent","submit","initPopup","popup","isInit","nodes","container","shadow","head","loader","content","textarea","buttonAccept","buttonReject","onAccept","hide","onReject","init","tmplNode","createElement","innerHTML","children","body","insertBefore","textContent","message","setTitle","text","setContent","show","isContentVisible","style","display","sendData","hasOwnProperty","setTextToPopup","sendActionRequest","data","alert","titleBar","textTitlePos","indexOf","textTitleDotPos","substr","trim","split","Function","call","String","callback","url","window","location","href","originId","inputs","input","name","value","originatorId","apply","action","callbackSuccess","callbackFailure","sessid","bitrix_sessid","ajax","method","timeout","dataType","processData","onsuccess","proxy","error","onfailure","ready"],"mappings":"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"}