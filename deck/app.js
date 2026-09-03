const angels = [
  {name:'АРХАНГЕЛ МИХАИЛ', color:'#313f59', seal:'М', themes:[
    ['ЗАЩИТА','Ты можешь не объяснять свою силу. Сегодня важно вернуть себе границы и перестать пропускать внутрь то, что тебе не принадлежит.','Где сегодня я отдаю больше энергии, чем хочу?','Сделай один ясный выбор в свою пользу.'],
    ['ВНУТРЕННЯЯ ОПОРА','Сила возвращается не через напряжение, а через решение остаться на своей стороне.','Что поможет мне почувствовать опору прямо сейчас?','Замедлись на минуту и почувствуй стопы, дыхание, спину.'],
    ['СМЕЛОСТЬ','Не жди полного отсутствия страха. Следующий шаг может быть тихим, но он уже меняет направление.','Какой шаг я давно откладываю?','Сделай сегодня его самую маленькую версию.'],
    ['ГРАНИЦЫ','Твоё «нет» не разрушает правильное. Оно освобождает пространство для того, что действительно твоё.','Где мне пора перестать соглашаться автоматически?','Не отвечай сразу. Дай себе паузу перед решением.'],
    ['ЯСНОЕ РЕШЕНИЕ','Когда внутри много голосов, выбери тот, после которого тело становится спокойнее.','Какое решение уже ощущается верным?','Убери один лишний вариант и оставь главное.'],
    ['ВОЗВРАЩЕНИЕ К СЕБЕ','Не всё, что требует твоего внимания, заслуживает твою энергию.','Что сегодня можно не нести дальше?','Отпусти одну чужую задачу, эмоцию или ожидание.']
  ]},
  {name:'АРХАНГЕЛ ГАВРИИЛ', color:'#6d5a74', seal:'Г', themes:[
    ['ГОЛОС','Сегодня твоё слово может открыть дверь, которую молчание держало закрытой.','Что я хочу сказать честно и мягко?','Сформулируй одну важную фразу без оправданий.'],
    ['ЗНАК','Ответ может прийти через разговор, сообщение или случайную фразу. Будь внимательна к повторениям.','Какой знак я готова заметить?','Запиши первое совпадение, которое отзовётся.'],
    ['НОВАЯ ВЕСТЬ','Пространство уже движется. Не торопи событие — подготовься принять его.','Что хорошее я допускаю в свою жизнь?','Освободи место: в расписании, мыслях или доме.'],
    ['ЯСНОЕ СЛОВО','Сложное становится проще, когда ты называешь его настоящим именем.','Что я больше не хочу смягчать для себя?','Назови ситуацию одним честным предложением.'],
    ['ТВОРЧЕСКИЙ ПОТОК','Не пытайся сразу сделать идеально. Сначала дай идее появиться.','Что хочет быть создано через меня?','Начни с черновика, заметки или первого движения.'],
    ['РАЗГОВОР','Один правильный разговор способен вернуть больше энергии, чем недели внутреннего диалога.','С кем мне важно прояснить пространство?','Выбери спокойный момент и говори от первого лица.']
  ]},
  {name:'АРХАНГЕЛ УРИИЛ', color:'#7a4e2f', seal:'У', themes:[
    ['ЯСНОСТЬ','Тебе не нужно видеть весь путь. Достаточно увидеть следующий честный шаг.','Что сейчас действительно главное?','Выбери одну задачу, которая двигает тебя вперёд.'],
    ['ЗЕМНАЯ МУДРОСТЬ','Ответ уже ближе к простому, чем кажется. Убери лишнее и посмотри на факты.','Что я усложняю?','Сократи решение до трёх конкретных действий.'],
    ['ВОЗМОЖНОСТЬ','Иногда дверь выглядит не как чудо, а как маленькое предложение, контакт или идея.','Какую возможность я раньше недооценивала?','Вернись к одному варианту, который быстро отбросила.'],
    ['ПОРЯДОК','Когда внешнее становится яснее, внутреннее тоже собирается.','Где мне нужен порядок?','Разбери один небольшой участок пространства или списка дел.'],
    ['РЕСУРС','Не всё решается усилием. Часть задач решается правильным распределением энергии.','На что уходит мой ресурс?','Убери сегодня один необязательный расход сил.'],
    ['СЛЕДУЮЩИЙ ШАГ','Большой путь не требует большого движения сегодня.','Какой шаг достаточно хорош на сегодня?','Сделай его до конца дня, не улучшая бесконечно.']
  ]},
  {name:'АРХАНГЕЛ РАФАИЛ', color:'#355a4a', seal:'Р', themes:[
    ['ВОССТАНОВЛЕНИЕ','Тело не мешает тебе — оно показывает, где давно нужна мягкость.','Что моё тело просит сегодня?','Дай себе десять спокойных минут без экрана.'],
    ['МЯГКОСТЬ','Не всё нужно преодолевать. Иногда самый сильный выбор — перестать давить на себя.','Где я могу быть к себе мягче?','Замени одну внутреннюю претензию на поддержку.'],
    ['ДЫХАНИЕ','Вернись в настоящий момент через тело. Здесь уже меньше угрозы, чем в мыслях.','Что изменится, если я замедлюсь?','Сделай пять длинных выдохов, длиннее вдоха.'],
    ['ОБНОВЛЕНИЕ','Не требуй от себя прежнего темпа после периода напряжения. Ты можешь начать по-новому.','Какой новый ритм мне подходит?','Снизь планку одной задачи до реального уровня.'],
    ['ЗАБОТА','Забота о себе сегодня — не награда после дел, а часть самого пути.','Что я обычно откладываю для себя?','Сделай это до того, как закончишь все дела.'],
    ['СПОКОЙСТВИЕ','Тебе не обязательно решать всё в состоянии тревоги.','Что можно решить позже, когда внутри тише?','Отложи одно не срочное решение на несколько часов.']
  ]},
  {name:'АРХАНГЕЛ ЧАМУИЛ', color:'#794a58', seal:'Ч', themes:[
    ['ЛЮБОВЬ','Любовь начинается не с идеальной близости, а с возвращения тепла внутрь себя.','Где я могу дать себе больше тепла?','Сделай один жест заботы без причины.'],
    ['БЛИЗОСТЬ','Не угадывай за другого. Настоящая близость появляется там, где есть живой вопрос.','Что мне важно узнать, а не предположить?','Задай один простой честный вопрос.'],
    ['ПРИТЯЖЕНИЕ','Когда ты возвращаешься к себе, меняется то, на что откликается мир.','Какое состояние я хочу излучать сегодня?','Выбери одежду, аромат или движение, которое его поддержит.'],
    ['СЕРДЦЕ','Не закрывай сердце из-за одной истории. Границы и открытость могут существовать вместе.','Что я готова впустить без потери себя?','Скажи «да» одной маленькой радости.'],
    ['ВСТРЕЧА','Некоторые встречи становятся возможными только после внутреннего разрешения.','К чему или кому я готова стать ближе?','Разреши себе проявиться первой в маленьком жесте.'],
    ['ТЕПЛО','Сегодня важнее не доказать, а почувствовать.','Где отношениям не хватает тепла?','Добавь в общение одно искреннее тёплое действие.']
  ]},
  {name:'АРХАНГЕЛ ИОФИИЛ', color:'#6f6235', seal:'И', themes:[
    ['КРАСОТА','Красота возвращает тебя к себе быстрее, чем ещё одна мысль.','Что сегодня делает моё пространство живым?','Добавь одну красивую деталь только для себя.'],
    ['ВДОХНОВЕНИЕ','Не выжимай идею. Создай условия, в которых ей захочется прийти.','Что меня действительно вдохновляет сейчас?','Дай себе двадцать минут без задачи и результата.'],
    ['ЛЁГКОСТЬ','Лёгкость не означает несерьёзность. Иногда это знак, что ты перестала сопротивляться.','Что можно сделать проще?','Убери один лишний шаг.'],
    ['ВЗГЛЯД','То, на что ты смотришь каждый день, формирует твоё состояние.','Чем я наполняю внимание?','Замени один источник шума на то, что тебя собирает.'],
    ['ЦЕННОСТЬ','Не нужно становиться больше, чтобы быть достойной большего.','Где я занижаю собственную ценность?','Сегодня не объясняй и не оправдывай одну свою потребность.'],
    ['УДОВОЛЬСТВИЕ','Удовольствие может быть способом вернуться в контакт с жизнью.','Что даёт мне настоящее живое «да»?','Включи это в сегодняшний день хотя бы на десять минут.']
  ]},
  {name:'АРХАНГЕЛ ЗАДКИИЛ', color:'#573d68', seal:'З', themes:[
    ['ОСВОБОЖДЕНИЕ','То, что завершилось, не обязано получать твою энергию ещё один день.','Что я готова оставить вчера?','Напиши одну фразу и физически порви лист.'],
    ['ПРОЩЕНИЕ','Прощение не отменяет границ. Оно возвращает твою энергию из старого события.','Что я больше не хочу носить внутри?','Выбери не оправдание, а внутреннее освобождение.'],
    ['НОВОЕ','Новое редко входит туда, где всё пространство занято прошлым.','Что мне пора освободить?','Закрой один незавершённый маленький цикл.'],
    ['ТРАНСФОРМАЦИЯ','Ты уже не обязана отвечать на старую ситуацию старой версией себя.','Как я могу выбрать иначе?','Сделай противоположное привычной автоматической реакции.'],
    ['ОТПУСКАНИЕ','Контроль не всегда защищает. Иногда он просто удерживает напряжение.','Что сегодня можно не контролировать?','Разреши одному процессу идти без постоянной проверки.'],
    ['ПЕРЕХОД','Между старым и новым бывает тихое пространство. Не спеши заполнять его.','Что рождается во мне сейчас?','Оставь сегодня немного пустого времени без планов.']
  ]}
];
const cards=[];
angels.forEach((a,ai)=>a.themes.forEach((t,ti)=>cards.push({angel:a.name,color:a.color,seal:a.seal,title:t[0],message:t[1],insight:t[2],action:t[3],code:String(ai+1).padStart(2,'0')+'·'+String(ti+1).padStart(2,'0')})));
const app=document.getElementById('app');
const todayKey=()=>new Date().toISOString().slice(0,10);
const fmtDate=()=>new Intl.DateTimeFormat('ru-RU',{day:'numeric',month:'long'}).format(new Date()).toUpperCase();
let chosen=null, clarifier=null;
function icon(){return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>'}
function shell(content){app.innerHTML=`<header class="topbar"><span>ALYA</span><span>ЖИВАЯ КОЛОДА</span><button class="icon-button" id="help" aria-label="Как пользоваться">${icon()}</button></header>${content}`;document.getElementById('help').onclick=showHelp}
function intro(){shell(`<section class="ritual intro-screen"><p class="eyebrow">ТВОЯ КАРТА НА ${fmtDate()}</p><h1>Задай вопрос,<br><em>который живёт внутри.</em></h1><button type="button" class="deck-stack" id="deck"><span></span><span></span><span class="card-back">✦</span></button><p class="deck-invitation">ПРИКОСНИСЬ К КОЛОДЕ</p><p class="quiet">Не ищи правильных слов.<br>Достаточно внутреннего намерения.</p></section>`);document.getElementById('deck').onclick=shuffle}
function shuffle(){shell(`<section class="ritual shuffle-screen"><p class="eyebrow">КОЛОДА СЛЫШИТ ТВОЁ НАМЕРЕНИЕ</p><h2>Останься<br><em>на мгновение внутри.</em></h2><div class="shuffle-cards">${[0,1,2,3,4].map(i=>`<div class="mini-back" style="--i:${i}"></div>`).join('')}</div><p class="quiet">Не выбирай умом.</p></section>`);setTimeout(choose,1350)}
function choose(){shell(`<section class="ritual choose-screen"><p class="eyebrow">ВЫБЕРИ ОДНУ</p><h2>Какая карта<br><em>зовёт тебя?</em></h2><div class="choice-row">${[0,1,2].map(i=>`<button class="choice-card" data-i="${i}" aria-label="Выбрать карту"><span>${i+1}</span></button>`).join('')}</div><p class="quiet">Первое движение обычно самое точное.</p></section>`);document.querySelectorAll('.choice-card').forEach(b=>b.onclick=()=>drawMain(+b.dataset.i))}
function seededPool(){let seed=[...todayKey()].reduce((a,c)=>a+c.charCodeAt(0),0);let arr=[...cards];for(let i=arr.length-1;i>0;i--){seed=(seed*9301+49297)%233280;let j=Math.floor(seed/233280*(i+1));[arr[i],arr[j]]=[arr[j],arr[i]]}return arr}
function drawMain(choice){const pool=seededPool();chosen=pool[(choice*11+new Date().getDate())%pool.length];localStorage.setItem('alya-main-'+todayKey(),JSON.stringify(chosen));result()}
function cardHTML(c,compact=false){return `<article class="oracle-card${compact?' compact':''}" style="--card-color:${c.color}"><div class="ornament">✦</div><div class="angel-seal"><i></i><span>${c.seal}</span><b>ALYA</b></div><div class="archangel">${c.angel}</div><div class="card-code">КОД ${c.code}</div><h2>${c.title}</h2><div class="gold-rule"></div><p class="message">${c.message}</p><div class="insight"><span>ВОПРОС ДЛЯ ТЕБЯ</span><p>${c.insight}</p></div><div class="action"><span>ЗАКРЕПИ СОСТОЯНИЕ</span><p>${c.action}</p></div><footer>ALYA • ЛИЧНОЕ ПОСЛАНИЕ ДНЯ</footer></article>`}
function result(){shell(`<section class="result-screen"><p class="eyebrow">ТВОЁ ПОСЛАНИЕ НА ${fmtDate()}</p>${cardHTML(chosen)}<div class="actions"><button class="primary-button" id="save">СОХРАНИТЬ КАРТУ</button><button class="text-action" id="clarify">✦ ОТКРЫТЬ УТОЧНЕНИЕ</button></div><div id="clarifier"></div><p class="tomorrow-note">Вернись завтра за новым посланием.<br>Пусть карта останется твоим якорем на сегодня.</p></section>`);document.getElementById('save').onclick=saveCard;document.getElementById('clarify').onclick=drawClarifier}
function drawClarifier(){const existing=localStorage.getItem('alya-clarifier-'+todayKey());if(existing) clarifier=JSON.parse(existing);else{const pool=seededPool().filter(c=>c.code!==chosen.code && c.angel!==chosen.angel);clarifier=pool[(new Date().getDate()*7)%pool.length];localStorage.setItem('alya-clarifier-'+todayKey(),JSON.stringify(clarifier))}document.getElementById('clarifier').innerHTML=`<div class="clarifier"><div class="clarifier-heading"><span>ВТОРАЯ КАРТА</span><small>Не новый вопрос. Уточнение к первому посланию.</small></div>${cardHTML(clarifier,true)}</div>`;document.getElementById('clarify').style.display='none'}
function saveCard(){const c=document.getElementById('saveCanvas'),x=c.getContext('2d');c.width=1080;c.height=1920;let g=x.createLinearGradient(0,0,1080,1920);g.addColorStop(0,'#181311');g.addColorStop(.58,chosen.color);g.addColorStop(1,'#251a17');x.fillStyle=g;x.fillRect(0,0,c.width,c.height);x.strokeStyle='#c2a36b';x.lineWidth=3;x.strokeRect(70,70,940,1780);x.textAlign='center';x.fillStyle='#f5e8df';x.font='34px Georgia';x.fillText(chosen.angel,540,330);x.fillStyle='#d7b5ad';x.font='22px Arial';x.fillText('КОД '+chosen.code,540,385);x.fillStyle='#f5e8df';x.font='64px Georgia';wrap(x,chosen.title,540,550,820,76);x.strokeStyle='#c9a76b';x.beginPath();x.moveTo(420,710);x.lineTo(660,710);x.stroke();x.font='40px Georgia';wrap(x,chosen.message,540,820,820,58);x.fillStyle='#d0ad9d';x.font='20px Arial';x.fillText('ВОПРОС ДЛЯ ТЕБЯ',540,1320);x.fillStyle='#f5e8df';x.font='34px Georgia';wrap(x,chosen.insight,540,1380,820,48);x.fillStyle='#c9a76b';x.font='18px Arial';x.fillText('ALYA • ЖИВАЯ КОЛОДА',540,1770);const a=document.createElement('a');a.download=`ALYA-карта-${todayKey()}.png`;a.href=c.toDataURL('image/png');a.click()}
function wrap(ctx,text,x,y,max,line){const words=text.split(' ');let l='',yy=y;for(const w of words){const t=l+w+' ';if(ctx.measureText(t).width>max&&l){ctx.fillText(l.trim(),x,yy);l=w+' ';yy+=line}else l=t}ctx.fillText(l.trim(),x,yy);return yy}
function showHelp(){const old=document.getElementById('guideOverlay');if(old)return;const d=document.createElement('div');d.id='guideOverlay';d.style.cssText='position:fixed;inset:0;background:#0009;z-index:99;display:grid;place-items:center;padding:20px';d.innerHTML=`<div class="guide-dialog" style="max-width:430px;border:1px solid;padding:26px;border-radius:18px"><h2>Как пользоваться</h2><ol><li>Сформулируй вопрос внутри, не обязательно вслух.</li><li>Прикоснись к колоде и выбери карту по первому импульсу.</li><li>Прочитай послание и вопрос к себе.</li><li>При желании открой одну карту-уточнение.</li><li>Сохрани карту на телефон как якорь состояния.</li></ol><p>Не ищи идеального смысла. Заметь то, что откликнулось первым.</p><button class="primary-button" id="closeGuide">ПОНЯТНО</button></div>`;document.body.appendChild(d);document.getElementById('closeGuide').onclick=()=>d.remove();d.onclick=e=>{if(e.target===d)d.remove()}}
const saved=localStorage.getItem('alya-main-'+todayKey());if(saved){chosen=JSON.parse(saved);result()}else intro();
