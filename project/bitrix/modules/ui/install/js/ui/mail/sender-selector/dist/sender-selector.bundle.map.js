{"version":3,"file":"sender-selector.bundle.map.js","names":["this","BX","UI","exports","main_core","main_core_events","main_loader","ui_entitySelector","ui_mail_providerShowcase","ui_mail_senderEditor","ui_iconSet_api_core","_","t","_t","_t2","_t3","_t4","_t5","senderEntityId","mailboxEntityId","senderPrefix","mailboxPrefix","_container","babelHelpers","classPrivateFieldLooseKey","_senderButton","_senderButtonTextNode","_loader","_isListUpdated","_isSenderAvailable","_createLoader","_renderContainer","_createSelector","_updateDialog","_loadItems","_updateSenderList","_addSender","_showLoader","_hideLoader","_getSelectorSenderId","_getSenderTypeByItemId","SenderSelector","constructor","options","_options$fieldValue","_options$isSenderAvai","Object","defineProperty","value","_getSenderTypeByItemId2","_getSelectorSenderId2","_hideLoader2","_showLoader2","_addSender2","_updateSenderList2","_loadItems2","_updateDialog2","_createSelector2","_renderContainer2","_createLoader2","writable","sender","fieldValue","length","fieldId","fieldName","classPrivateFieldLooseBase","isSenderAvailable","selectCallback","mailboxes","render","renderTo","targetContainer","Type","isDomNode","Dom","append","setSender","senderId","name","email","type","prefix","selectedItemId","senderName","senderEmail","selectorText","input","querySelector","innerText","Loc","getMessage","title","icon","showDialog","targetNode","selectedSender","senderDialog","getItems","showProviderShowcase","setTargetNode","show","addSenderCallback","ProviderShowcase","openSlider","isSender","setSenderCallback","updateSenderList","Loader","target","size","mode","_this$sender","_this$sender2","_this$sender3","Icon","Actions","CHEVRON_DOWN","color","getComputedStyle","document","body","getPropertyValue","Tag","root","senderInput","footerHandler","hide","footer","linkClickHandler","baseEvent","data","event","preventDefault","item","node","getItem","dialog","getDialog","customData","getCustomData","entityId","SidePanel","Instance","open","get","width","cacheable","events","onClose","AliasEditor","Dialog","height","multiple","enableSearch","dropdownMode","showAvatars","compactView","selectedItem","getData","selectedItemName","selectedItemEmail","id","Event","bind","senders","removeItems","unsafe","forEach","select","items","ajax","runAction","then","response","catch","async","href","editHref","addItem","tabs","link","deselectable","linkTitle","formated","style","entityType","split","Mail","EntitySelector","IconSet"],"sources":["sender-selector.bundle.js"],"mappings":"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"}