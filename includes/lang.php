<?php
// Simple English / Sinhala / Tamil interface labels
if (session_status() === PHP_SESSION_NONE) session_start();

$supported = ['en', 'si', 'ta'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $supported, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}
$LANG = $_SESSION['lang'] ?? 'en';

$strings = [
    'en' => [
        'home' => 'Home', 'planner' => 'My Trip Plan', 'admin' => 'Admin',
        'hero_title' => 'Plan your one-day trip around Gampola',
        'hero_text' => 'Discover temples, waterfalls, viewpoints and heritage sites within 25 km of Gampola, then build a simple one-day itinerary.',
        'search' => 'Search places...', 'all' => 'All', 'historical' => 'Historical', 'religious' => 'Religious',
        'natural' => 'Natural', 'recreational' => 'Recreational',
        'view_details' => 'View details', 'add_plan' => 'Add to plan', 'in_plan' => 'In plan (remove)',
        'no_results' => 'No places match your search.', 'map_title' => 'Map of attractions',
        'from_gampola' => 'from Gampola', 'opening_hours' => 'Opening hours', 'entrance_fee' => 'Entrance fee',
        'facilities' => 'Facilities', 'directions' => 'Get directions', 'nearby' => 'Nearby attractions',
        'back' => 'Back to all places', 'plan_title' => 'My one-day trip plan',
        'start_time' => 'Start time from Gampola', 'empty_plan' => 'Your plan is empty. Add some places first.',
        'print' => 'Print / Save as PDF', 'clear' => 'Clear plan', 'remove' => 'Remove',
        'total' => 'Total', 'travel' => 'Travel', 'visit' => 'Visit', 'arrive' => 'Arrive', 'leave' => 'Leave',
        'plan_note' => 'This plan is only a recommendation. Travel times are estimates and opening hours may change.',
        'warn_late' => 'Your plan ends after 6:30 PM. Consider removing a place.',
        'footer' => 'Information only - no bookings or payments are handled by this site.',
        'distance' => 'Distance', 'category' => 'Category', 'map_plan' => 'Route on map',
    ],
    'si' => [
        'home' => 'මුල් පිටුව', 'planner' => 'මගේ චාරිකා සැලැස්ම', 'admin' => 'පරිපාලක',
        'hero_title' => 'ගම්පොළ ආසන්නයේ එක්දින චාරිකාවක් සැලසුම් කරන්න',
        'hero_text' => 'ගම්පොළ සිට කි.මී. 25ක් ඇතුළත විහාර, දිය ඇල්ල, දර්ශන ස්ථාන සහ උරුම ස්ථාන සොයා එක්දින සැලැස්මක් සාදන්න.',
        'search' => 'ස්ථාන සොයන්න...', 'all' => 'සියල්ල', 'historical' => 'ඓතිහාසික', 'religious' => 'ආගමික',
        'natural' => 'ස්වභාවික', 'recreational' => 'විනෝදාත්මක',
        'view_details' => 'විස්තර බලන්න', 'add_plan' => 'සැලැස්මට එක් කරන්න', 'in_plan' => 'සැලැස්මේ ඇත (ඉවත් කරන්න)',
        'no_results' => 'ගැලපෙන ස්ථාන නැත.', 'map_title' => 'ස්ථාන සිතියම',
        'from_gampola' => 'ගම්පොළ සිට', 'opening_hours' => 'විවෘත වේලාවන්', 'entrance_fee' => 'ප්‍රවේශ ගාස්තුව',
        'facilities' => 'පහසුකම්', 'directions' => 'මාර්ග සැලැස්ම', 'nearby' => 'ආසන්න ස්ථාන',
        'back' => 'සියලු ස්ථාන වෙත', 'plan_title' => 'මගේ එක්දින චාරිකා සැලැස්ම',
        'start_time' => 'ගම්පොළින් පිටත් වන වේලාව', 'empty_plan' => 'ඔබේ සැලැස්ම හිස්ය. කරුණාකර ස්ථාන එක් කරන්න.',
        'print' => 'මුද්‍රණය / PDF ලෙස සුරකින්න', 'clear' => 'සැලැස්ම මකන්න', 'remove' => 'ඉවත් කරන්න',
        'total' => 'මුළු', 'travel' => 'ගමන', 'visit' => 'නැරඹීම', 'arrive' => 'පැමිණීම', 'leave' => 'පිටවීම',
        'plan_note' => 'මෙය නිර්දේශයක් පමණි. ගමන් කාලය ආසන්න අගයන් වන අතර විවෘත වේලාවන් වෙනස් විය හැක.',
        'warn_late' => 'ඔබේ සැලැස්ම සවස 6.30න් පසු අවසන් වේ. ස්ථානයක් ඉවත් කරන්න.',
        'footer' => 'තොරතුරු සඳහා පමණි - වෙන්කිරීම් හෝ ගෙවීම් සිදු නොවේ.',
        'distance' => 'දුර', 'category' => 'වර්ගය', 'map_plan' => 'සිතියමේ මාර්ගය',
    ],
    'ta' => [
        'home' => 'முகப்பு', 'planner' => 'எனது பயணத் திட்டம்', 'admin' => 'நிர்வாகி',
        'hero_title' => 'கம்பளையைச் சுற்றி ஒரு நாள் பயணத்தைத் திட்டமிடுங்கள்',
        'hero_text' => 'கம்பளையிலிருந்து 25 கி.மீ. தூரத்திற்குள் உள்ள கோவில்கள், நீர்வீழ்ச்சிகள், பார்வை இடங்களைக் கண்டறிந்து ஒரு நாள் திட்டத்தை உருவாக்குங்கள்.',
        'search' => 'இடங்களைத் தேடுங்கள்...', 'all' => 'அனைத்தும்', 'historical' => 'வரலாற்று', 'religious' => 'சமய',
        'natural' => 'இயற்கை', 'recreational' => 'பொழுதுபோக்கு',
        'view_details' => 'விவரங்கள்', 'add_plan' => 'திட்டத்தில் சேர்', 'in_plan' => 'திட்டத்தில் உள்ளது (நீக்கு)',
        'no_results' => 'பொருந்தும் இடங்கள் இல்லை.', 'map_title' => 'இடங்களின் வரைபடம்',
        'from_gampola' => 'கம்பளையிலிருந்து', 'opening_hours' => 'திறந்திருக்கும் நேரம்', 'entrance_fee' => 'நுழைவுக் கட்டணம்',
        'facilities' => 'வசதிகள்', 'directions' => 'வழிகாட்டி', 'nearby' => 'அருகிலுள்ள இடங்கள்',
        'back' => 'அனைத்து இடங்களுக்கும்', 'plan_title' => 'எனது ஒரு நாள் பயணத் திட்டம்',
        'start_time' => 'கம்பளையிலிருந்து புறப்படும் நேரம்', 'empty_plan' => 'உங்கள் திட்டம் காலியாக உள்ளது. இடங்களைச் சேர்க்கவும்.',
        'print' => 'அச்சிடு / PDF ஆக சேமி', 'clear' => 'திட்டத்தை அழி', 'remove' => 'நீக்கு',
        'total' => 'மொத்தம்', 'travel' => 'பயணம்', 'visit' => 'பார்வை', 'arrive' => 'வருகை', 'leave' => 'புறப்பாடு',
        'plan_note' => 'இது ஒரு பரிந்துரை மட்டுமே. பயண நேரங்கள் மதிப்பீடுகள்; திறக்கும் நேரங்கள் மாறலாம்.',
        'warn_late' => 'உங்கள் திட்டம் மாலை 6.30க்குப் பிறகு முடிகிறது. ஓர் இடத்தை நீக்கவும்.',
        'footer' => 'தகவலுக்காக மட்டும் - முன்பதிவு அல்லது கட்டணம் இல்லை.',
        'distance' => 'தூரம்', 'category' => 'வகை', 'map_plan' => 'வரைபடத்தில் பாதை',
    ],
];

// t('key') returns the label in the chosen language (falls back to English)
function t($key) {
    global $strings, $LANG;
    return $strings[$LANG][$key] ?? $strings['en'][$key] ?? $key;
}
