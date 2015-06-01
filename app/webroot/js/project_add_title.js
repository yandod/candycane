/**
 * Created by Sergey Prokhorov <sergey_prokhorov@list.ru> on 02.05.15.
 */
//transliteration to en for cyrillic group languages
function cyrillicTranslite(str){
    var arr={'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ж':'g', 'з':'z', 'и':'i', 'й':'y', 'к':'k', 'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 'ф':'f', 'ы':'i', 'э':'e', 'А':'A', 'Б':'B', 'В':'V', 'Г':'G', 'Д':'D', 'Е':'E', 'Ж':'G', 'З':'Z', 'И':'I', 'Й':'Y', 'К':'K', 'Л':'L', 'М':'M', 'Н':'N', 'О':'O', 'П':'P', 'Р':'R', 'С':'S', 'Т':'T', 'У':'U', 'Ф':'F', 'Ы':'I', 'Э':'E', 'ё':'yo', 'х':'h', 'ц':'ts', 'ч':'ch', 'ш':'sh', 'щ':'shch', 'ъ':'', 'ь':'', 'ю':'yu', 'я':'ya', 'Ё':'YO', 'Х':'H', 'Ц':'TS', 'Ч':'CH', 'Ш':'SH', 'Щ':'SHCH', 'Ъ':'', 'Ь':'',
        'Ю':'YU', 'Я':'YA'};
    var replacer=function(a){return arr[a]||a};
    return str.replace(/[А-яёЁ]/g,replacer)
}
function generateProjectTitle(Title, Identifier) {
    Title.onkeyup = function(evt) {
        var identifier= Title.value;
        identifier = cyrillicTranslite(identifier);
        identifier = identifier.replace(/[^a-z0-9_]+/gi, '-'); // remaining non-alphanumeric => hyphen
        identifier = identifier.replace(/^[-_\d]*|[-_]*$/g, ''); // remove hyphens/underscores and numbers at beginning and hyphens/underscores at end
        identifier = identifier.toLowerCase(); // to lower
        identifier = identifier.substr(0, 100); // max characters
        Identifier.value = identifier;
    };
}
generateProjectTitle(document.getElementById('ProjectName'), document.getElementById('ProjectIdentifier'));