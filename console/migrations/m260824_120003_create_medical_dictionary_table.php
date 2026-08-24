<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%medical_dictionary}}`.
 */
class m260824_120003_create_medical_dictionary_table extends Migration
{
   /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
      $this->createTable('{{%medical_dictionary}}', [
         'id' => $this->primaryKey(),
         'category_id' => $this->integer()->notNull(),
         'type' => $this->integer(),
         'name_ru' => $this->string()->notNull(),
         'name_en' => $this->string()->notNull(),
         'name_uz' => $this->string()->notNull(),
         'slug_ru' => $this->string()->notNull(),
         'slug_en' => $this->string()->notNull(),
         'slug_uz' => $this->string()->notNull(),
         'desc_ru' => $this->string()->notNull(),
         'desc_en' => $this->string()->notNull(),
         'desc_uz' => $this->string()->notNull(),
         'content_ru' => $this->text()->notNull(),
         'content_en' => $this->text()->notNull(),
         'content_uz' => $this->text()->notNull(),
         'seo_title_ru' => $this->string(),
         'seo_title_en' => $this->string(),
         'seo_title_uz' => $this->string(),
         'seo_desc_ru' => $this->string(),
         'seo_desc_en' => $this->string(),
         'seo_desc_uz' => $this->string(),
         'created_at' => $this->integer()->notNull(),
         'updated_at' => $this->integer()->notNull(),
         'status' => $this->integer()->notNull()->defaultValue(1),
      ]);
      $this->createIndex(
         'idx-medical_dictionary-category_id',
         '{{%medical_dictionary}}',
         'category_id'
      );
      
      $this->createIndex(
         'idx-medical_dictionary-type',
         '{{%medical_dictionary}}',
         'type'
      );
      
      $this->createIndex(
         'idx-medical_dictionary-status',
         '{{%medical_dictionary}}',
         'status'
      );
      
      $this->createIndex(
         'uq-medical_dictionary-slug_ru',
         '{{%medical_dictionary}}',
         'slug_ru',
         true
      );
      
      $this->createIndex(
         'uq-medical_dictionary-slug_en',
         '{{%medical_dictionary}}',
         'slug_en',
         true
      );
      
      $this->createIndex(
         'uq-medical_dictionary-slug_uz',
         '{{%medical_dictionary}}',
         'slug_uz',
         true
      );
      
      $time = time();
      
      $term = static function (int $category, int $type, array $name, array $slug, array $desc, array $content) use ($time) {
         return [
            $category,
            $type,
            
            $name['ru'],
            $name['en'],
            $name['uz'],
            
            $slug['ru'],
            $slug['en'],
            $slug['uz'],
            
            $desc['ru'],
            $desc['en'],
            $desc['uz'],
            
            '<p>' . $desc['ru'] . '</p><p>' . $content['ru'] . '</p>',
            '<p>' . $desc['en'] . '</p><p>' . $content['en'] . '</p>',
            '<p>' . $desc['uz'] . '</p><p>' . $content['uz'] . '</p>',
            
            mb_substr(
               $name['ru'] . ' — что это? Медицинский термин | Meros',
               0,
               255
            ),
            mb_substr(
               $name['en'] . ' — Definition and Medical Meaning | Meros',
               0,
               255
            ),
            mb_substr(
               $name['uz'] . ' nima? Tibbiy atama ma’nosi | Meros',
               0,
               255
            ),
            
            mb_substr(
               $name['ru'] . ': ' . $desc['ru'] . ' Значение медицинского термина в словаре Meros.',
               0,
               255
            ),
            mb_substr(
               $name['en'] . ': ' . $desc['en'] . ' Learn the meaning of this medical term in the Meros medical dictionary.',
               0,
               255
            ),
            mb_substr(
               $name['uz'] . ': ' . $desc['uz'] . ' Meros tibbiy lug‘atida ushbu atamaning ma’nosini bilib oling.',
               0,
               255
            ),
            
            $time,
            $time,
            1,
         ];
      };
      
      $terms = [];
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Артериальная гипертензия',
            'en' => 'Hypertension',
            'uz' => 'Arterial gipertenziya',
         ],
         [
            'ru' => 'arterialnaya-gipertenziya',
            'en' => 'hypertension',
            'uz' => 'arterial-gipertenziya',
         ],
         [
            'ru' => 'Состояние, при котором артериальное давление устойчиво превышает нормальные значения.',
            'en' => 'A condition in which blood pressure in the arteries remains persistently elevated.',
            'uz' => 'Arterial qon bosimi doimiy ravishda me’yoriy ko‘rsatkichlardan yuqori bo‘ladigan holat.',
         ],
         [
            'ru' => 'Артериальная гипертензия является важным фактором риска заболеваний сердца, сосудов, головного мозга и почек.',
            'en' => 'Hypertension is an important risk factor for diseases of the heart, blood vessels, brain and kidneys.',
            'uz' => 'Arterial gipertenziya yurak, qon tomirlari, miya va buyrak kasalliklari uchun muhim xavf omilidir.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Артериальная гипотензия',
            'en' => 'Hypotension',
            'uz' => 'Arterial gipotenziya',
         ],
         [
            'ru' => 'arterialnaya-gipotenzia',
            'en' => 'hypotension',
            'uz' => 'arterial-gipotenziya',
         ],
         [
            'ru' => 'Состояние, характеризующееся снижением артериального давления.',
            'en' => 'A condition characterized by lower-than-usual blood pressure.',
            'uz' => 'Arterial qon bosimining odatdagidan past bo‘lishi bilan tavsiflanadigan holat.',
         ],
         [
            'ru' => 'У некоторых людей низкое давление является индивидуальной особенностью, тогда как в других случаях оно может сопровождаться слабостью или головокружением.',
            'en' => 'Low blood pressure may be normal for some people but in other cases can be associated with weakness or dizziness.',
            'uz' => 'Ba’zi odamlarda past qon bosimi individual xususiyat bo‘lishi mumkin, boshqa hollarda esa holsizlik yoki bosh aylanishi bilan kechishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Тахикардия',
            'en' => 'Tachycardia',
            'uz' => 'Taxikardiya',
         ],
         [
            'ru' => 'tahikardiya',
            'en' => 'tachycardia',
            'uz' => 'taxikardiya',
         ],
         [
            'ru' => 'Учащённый сердечный ритм, обычно более 100 ударов в минуту у взрослого человека в состоянии покоя.',
            'en' => 'A fast heart rhythm, typically more than 100 beats per minute in a resting adult.',
            'uz' => 'Tinch holatdagi katta yoshli odamda yurak urish tezligining odatda daqiqasiga 100 martadan yuqori bo‘lishi.',
         ],
         [
            'ru' => 'Тахикардия может быть физиологической реакцией на нагрузку или сопровождать различные заболевания и нарушения сердечного ритма.',
            'en' => 'Tachycardia can occur as a normal response to physical activity or as part of various diseases and heart rhythm disorders.',
            'uz' => 'Taxikardiya jismoniy faollikka tabiiy javob sifatida yoki turli kasalliklar va yurak ritmi buzilishlarida uchrashi mumkin.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Брадикардия',
            'en' => 'Bradycardia',
            'uz' => 'Bradikardiya',
         ],
         [
            'ru' => 'bradikardiya',
            'en' => 'bradycardia',
            'uz' => 'bradikardiya',
         ],
         [
            'ru' => 'Замедленный сердечный ритм, обычно менее 60 ударов в минуту у взрослого человека в покое.',
            'en' => 'A slow heart rhythm, typically fewer than 60 beats per minute in a resting adult.',
            'uz' => 'Tinch holatdagi katta yoshli odamda yurak urish tezligining odatda daqiqasiga 60 martadan kam bo‘lishi.',
         ],
         [
            'ru' => 'У тренированных людей брадикардия может быть физиологической, однако иногда она связана с нарушением работы проводящей системы сердца.',
            'en' => 'Bradycardia may be normal in trained individuals, although it can also be associated with disorders of the heart conduction system.',
            'uz' => 'Jismonan chiniqqan odamlarda bradikardiya fiziologik bo‘lishi mumkin, ayrim hollarda esa yurak o‘tkazuvchi tizimi buzilishi bilan bog‘liq bo‘ladi.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Аритмия',
            'en' => 'Arrhythmia',
            'uz' => 'Aritmiya',
         ],
         [
            'ru' => 'aritmiya',
            'en' => 'arrhythmia',
            'uz' => 'aritmiya',
         ],
         [
            'ru' => 'Нарушение нормальной частоты, регулярности или последовательности сердечных сокращений.',
            'en' => 'A disturbance in the normal rate, rhythm or sequence of heartbeats.',
            'uz' => 'Yurak urish tezligi, muntazamligi yoki ketma-ketligining buzilishi.',
         ],
         [
            'ru' => 'Аритмии включают множество видов нарушений ритма — от относительно безобидных до потенциально опасных.',
            'en' => 'Arrhythmias include many types of rhythm disturbances, ranging from relatively harmless to potentially serious.',
            'uz' => 'Aritmiyalar nisbatan zararsiz holatlardan jiddiy ritm buzilishlarigacha bo‘lgan ko‘plab turlarni o‘z ichiga oladi.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Инфаркт миокарда',
            'en' => 'Myocardial infarction',
            'uz' => 'Miokard infarkti',
         ],
         [
            'ru' => 'infarkt-miokarda',
            'en' => 'myocardial-infarction',
            'uz' => 'miokard-infarkti',
         ],
         [
            'ru' => 'Повреждение участка сердечной мышцы вследствие длительного нарушения его кровоснабжения.',
            'en' => 'Damage to part of the heart muscle caused by prolonged interruption of its blood supply.',
            'uz' => 'Yurak mushagining bir qismiga qon yetib kelishi uzoq vaqt buzilishi natijasida yuzaga keladigan shikastlanish.',
         ],
         [
            'ru' => 'Инфаркт миокарда часто возникает из-за нарушения кровотока по коронарной артерии и относится к неотложным состояниям.',
            'en' => 'Myocardial infarction commonly results from disrupted blood flow through a coronary artery and is a medical emergency.',
            'uz' => 'Miokard infarkti ko‘pincha koronar arteriyada qon oqimi buzilishi natijasida yuzaga keladi va shoshilinch tibbiy holat hisoblanadi.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Стенокардия',
            'en' => 'Angina pectoris',
            'uz' => 'Stenokardiya',
         ],
         [
            'ru' => 'stenokardiya',
            'en' => 'angina-pectoris',
            'uz' => 'stenokardiya',
         ],
         [
            'ru' => 'Боль или дискомфорт в грудной клетке, возникающие при недостаточном кровоснабжении сердечной мышцы.',
            'en' => 'Chest pain or discomfort caused by insufficient blood flow to the heart muscle.',
            'uz' => 'Yurak mushagiga qon yetarli kelmasligi sababli yuzaga keladigan ko‘krak qafasidagi og‘riq yoki noqulaylik.',
         ],
         [
            'ru' => 'Стенокардия является одним из характерных проявлений ишемической болезни сердца.',
            'en' => 'Angina is a common manifestation of coronary artery disease.',
            'uz' => 'Stenokardiya yurak ishemik kasalligining keng tarqalgan belgilaridan biridir.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Сердечная недостаточность',
            'en' => 'Heart failure',
            'uz' => 'Yurak yetishmovchiligi',
         ],
         [
            'ru' => 'serdechnaya-nedostatochnost',
            'en' => 'heart-failure',
            'uz' => 'yurak-yetishmovchiligi',
         ],
         [
            'ru' => 'Состояние, при котором сердце не способно эффективно обеспечивать организм необходимым объёмом крови.',
            'en' => 'A condition in which the heart cannot pump blood effectively enough to meet the body’s needs.',
            'uz' => 'Yurak organizm ehtiyojini qondirish uchun qonni yetarlicha samarali hayday olmaydigan holat.',
         ],
         [
            'ru' => 'Сердечная недостаточность может сопровождаться одышкой, утомляемостью и накоплением жидкости в тканях.',
            'en' => 'Heart failure may be associated with shortness of breath, fatigue and fluid accumulation in the tissues.',
            'uz' => 'Yurak yetishmovchiligi nafas qisishi, charchoq va to‘qimalarda suyuqlik to‘planishi bilan kechishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         2, 1,
         [
            'ru' => 'Атеросклероз',
            'en' => 'Atherosclerosis',
            'uz' => 'Ateroskleroz',
         ],
         [
            'ru' => 'ateroskleroz',
            'en' => 'atherosclerosis',
            'uz' => 'ateroskleroz',
         ],
         [
            'ru' => 'Хронический процесс, при котором в стенках артерий формируются атеросклеротические бляшки.',
            'en' => 'A chronic process in which plaques develop within the walls of arteries.',
            'uz' => 'Arteriya devorlarida aterosklerotik blyashkalar hosil bo‘lishi bilan kechadigan surunkali jarayon.',
         ],
         [
            'ru' => 'Атеросклероз способен сужать просвет сосудов и нарушать кровоснабжение органов.',
            'en' => 'Atherosclerosis can narrow arteries and reduce blood supply to organs.',
            'uz' => 'Ateroskleroz tomirlar bo‘shlig‘ini toraytirib, a’zolarning qon bilan ta’minlanishini buzishi mumkin.',
         ]
      );
      $terms[] = $term(
         3, 1,
         [
            'ru' => 'Инсульт',
            'en' => 'Stroke',
            'uz' => 'Insult',
         ],
         [
            'ru' => 'insult',
            'en' => 'stroke',
            'uz' => 'insult',
         ],
         [
            'ru' => 'Острое нарушение кровоснабжения головного мозга, приводящее к повреждению мозговой ткани.',
            'en' => 'An acute disruption of blood supply to the brain that results in damage to brain tissue.',
            'uz' => 'Miya qon ta’minotining keskin buzilishi natijasida miya to‘qimasi shikastlanadigan holat.',
         ],
         [
            'ru' => 'Инсульт может быть ишемическим вследствие закупорки сосуда или геморрагическим вследствие кровоизлияния.',
            'en' => 'A stroke may be ischemic because of a blocked blood vessel or hemorrhagic because of bleeding.',
            'uz' => 'Insult qon tomiri berkilishi natijasidagi ishemik yoki qon ketishi natijasidagi gemorragik turda bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         3, 1,
         [
            'ru' => 'Мигрень',
            'en' => 'Migraine',
            'uz' => 'Migren',
         ],
         [
            'ru' => 'migren',
            'en' => 'migraine',
            'uz' => 'migren',
         ],
         [
            'ru' => 'Неврологическое заболевание, характеризующееся повторяющимися приступами головной боли.',
            'en' => 'A neurological disorder characterized by recurrent attacks of headache.',
            'uz' => 'Takroriy bosh og‘rig‘i xurujlari bilan tavsiflanadigan nevrologik kasallik.',
         ],
         [
            'ru' => 'Приступы мигрени могут сопровождаться тошнотой и повышенной чувствительностью к свету или звуку.',
            'en' => 'Migraine attacks may be accompanied by nausea and increased sensitivity to light or sound.',
            'uz' => 'Migren xurujlari ko‘ngil aynishi, yorug‘lik yoki tovushga yuqori sezuvchanlik bilan kechishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         3, 1,
         [
            'ru' => 'Эпилепсия',
            'en' => 'Epilepsy',
            'uz' => 'Epilepsiya',
         ],
         [
            'ru' => 'epilepsiya',
            'en' => 'epilepsy',
            'uz' => 'epilepsiya',
         ],
         [
            'ru' => 'Неврологическое заболевание, характеризующееся склонностью к повторным эпилептическим приступам.',
            'en' => 'A neurological disorder characterized by a tendency to have recurrent epileptic seizures.',
            'uz' => 'Takroriy epileptik xurujlarga moyillik bilan tavsiflanadigan nevrologik kasallik.',
         ],
         [
            'ru' => 'Приступы связаны с временным нарушением электрической активности головного мозга и могут проявляться по-разному.',
            'en' => 'Seizures result from temporary disturbances of electrical activity in the brain and may have different manifestations.',
            'uz' => 'Xurujlar miyaning elektr faolligidagi vaqtinchalik buzilish bilan bog‘liq bo‘lib, turlicha namoyon bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         3, 2,
         [
            'ru' => 'Тремор',
            'en' => 'Tremor',
            'uz' => 'Tremor',
         ],
         [
            'ru' => 'tremor',
            'en' => 'tremor',
            'uz' => 'tremor',
         ],
         [
            'ru' => 'Непроизвольные ритмические колебательные движения одной или нескольких частей тела.',
            'en' => 'Involuntary rhythmic shaking movements affecting one or more parts of the body.',
            'uz' => 'Tananing bir yoki bir nechta qismlarida kuzatiladigan ixtiyorsiz ritmik titrash harakatlari.',
         ],
         [
            'ru' => 'Тремор может возникать самостоятельно либо быть симптомом неврологических, метаболических и других состояний.',
            'en' => 'Tremor may occur independently or as a symptom of neurological, metabolic or other conditions.',
            'uz' => 'Tremor mustaqil ravishda yoki nevrologik, metabolik va boshqa holatlarning belgisi sifatida paydo bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         3, 2,
         [
            'ru' => 'Обморок',
            'en' => 'Syncope',
            'uz' => 'Sinkopa',
         ],
         [
            'ru' => 'obmorok-sinkopa',
            'en' => 'syncope',
            'uz' => 'sinkopa',
         ],
         [
            'ru' => 'Кратковременная потеря сознания, обычно связанная с временным снижением кровоснабжения головного мозга.',
            'en' => 'A brief loss of consciousness usually associated with a temporary reduction in blood flow to the brain.',
            'uz' => 'Odatda miyaga qon oqimining vaqtincha kamayishi bilan bog‘liq qisqa muddatli hushdan ketish.',
         ],
         [
            'ru' => 'После синкопального эпизода сознание обычно восстанавливается самостоятельно.',
            'en' => 'Consciousness usually returns spontaneously after a syncopal episode.',
            'uz' => 'Sinkopa epizodidan so‘ng hush odatda o‘z-o‘zidan tiklanadi.',
         ]
      );
      $terms[] = $term(
         4, 1,
         [
            'ru' => 'Пневмония',
            'en' => 'Pneumonia',
            'uz' => 'Pnevmoniya',
         ],
         [
            'ru' => 'pnevmoniya',
            'en' => 'pneumonia',
            'uz' => 'pnevmoniya',
         ],
         [
            'ru' => 'Воспалительное заболевание лёгочной ткани, чаще всего связанное с инфекцией.',
            'en' => 'An inflammatory disease of lung tissue, most commonly associated with infection.',
            'uz' => 'O‘pka to‘qimasining yallig‘lanish kasalligi bo‘lib, ko‘pincha infeksiya bilan bog‘liq.',
         ],
         [
            'ru' => 'При пневмонии воспаление может затрагивать альвеолы и нарушать нормальный газообмен в лёгких.',
            'en' => 'In pneumonia, inflammation may affect the alveoli and interfere with normal gas exchange in the lungs.',
            'uz' => 'Pnevmoniyada yallig‘lanish alveolalarni zararlashi va o‘pkadagi normal gaz almashinuvini buzishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         4, 1,
         [
            'ru' => 'Бронхит',
            'en' => 'Bronchitis',
            'uz' => 'Bronxit',
         ],
         [
            'ru' => 'bronhit',
            'en' => 'bronchitis',
            'uz' => 'bronxit',
         ],
         [
            'ru' => 'Воспаление слизистой оболочки бронхов.',
            'en' => 'Inflammation of the lining of the bronchial tubes.',
            'uz' => 'Bronxlar shilliq qavatining yallig‘lanishi.',
         ],
         [
            'ru' => 'Бронхит часто сопровождается кашлем и может иметь острое или хроническое течение.',
            'en' => 'Bronchitis is commonly associated with cough and may have an acute or chronic course.',
            'uz' => 'Bronxit ko‘pincha yo‘tal bilan kechadi va o‘tkir yoki surunkali shaklda bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         4, 1,
         [
            'ru' => 'Бронхиальная астма',
            'en' => 'Asthma',
            'uz' => 'Bronxial astma',
         ],
         [
            'ru' => 'bronhialnaya-astma',
            'en' => 'asthma',
            'uz' => 'bronxial-astma',
         ],
         [
            'ru' => 'Хроническое заболевание дыхательных путей, связанное с воспалением и периодическим сужением бронхов.',
            'en' => 'A chronic airway disease involving inflammation and episodes of narrowing of the bronchial tubes.',
            'uz' => 'Nafas yo‘llarining yallig‘lanishi va bronxlarning davriy torayishi bilan bog‘liq surunkali kasallik.',
         ],
         [
            'ru' => 'Астма может проявляться свистящим дыханием, кашлем, одышкой и ощущением сдавления в груди.',
            'en' => 'Asthma may cause wheezing, coughing, shortness of breath and chest tightness.',
            'uz' => 'Astma hushtaksimon nafas, yo‘tal, nafas qisishi va ko‘krak qafasida siqilish hissi bilan namoyon bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         4, 2,
         [
            'ru' => 'Одышка',
            'en' => 'Dyspnea',
            'uz' => 'Nafas qisishi',
         ],
         [
            'ru' => 'odyshka',
            'en' => 'dyspnea',
            'uz' => 'nafas-qisishi',
         ],
         [
            'ru' => 'Субъективное ощущение затруднённого или недостаточного дыхания.',
            'en' => 'The subjective sensation of difficult or uncomfortable breathing.',
            'uz' => 'Nafas olish qiyinlashgani yoki havo yetishmayotgandek subyektiv his.',
         ],
         [
            'ru' => 'Одышка может наблюдаться при заболеваниях дыхательной и сердечно-сосудистой систем, а также при физической нагрузке.',
            'en' => 'Dyspnea may occur in respiratory or cardiovascular disease and can also occur during physical exertion.',
            'uz' => 'Nafas qisishi nafas olish yoki yurak-qon tomir tizimi kasalliklarida, shuningdek jismoniy zo‘riqishda kuzatilishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         4, 2,
         [
            'ru' => 'Гипоксия',
            'en' => 'Hypoxia',
            'uz' => 'Gipoksiya',
         ],
         [
            'ru' => 'gipoksiya',
            'en' => 'hypoxia',
            'uz' => 'gipoksiya',
         ],
         [
            'ru' => 'Состояние недостаточного снабжения тканей организма кислородом.',
            'en' => 'A state in which body tissues receive an insufficient supply of oxygen.',
            'uz' => 'Organizm to‘qimalarining kislorod bilan yetarli darajada ta’minlanmaslik holati.',
         ],
         [
            'ru' => 'Гипоксия может возникать при нарушении дыхания, кровообращения или доставки кислорода кровью.',
            'en' => 'Hypoxia may result from problems involving breathing, circulation or oxygen transport in the blood.',
            'uz' => 'Gipoksiya nafas olish, qon aylanishi yoki qon orqali kislorod tashilishining buzilishi natijasida yuzaga kelishi mumkin.',
         ]
      );
      $terms[] = $term(
         6, 1,
         [
            'ru' => 'Сахарный диабет',
            'en' => 'Diabetes mellitus',
            'uz' => 'Qandli diabet',
         ],
         [
            'ru' => 'saharnyy-diabet',
            'en' => 'diabetes-mellitus',
            'uz' => 'qandli-diabet',
         ],
         [
            'ru' => 'Группа метаболических заболеваний, характеризующихся повышенным уровнем глюкозы в крови.',
            'en' => 'A group of metabolic diseases characterized by elevated blood glucose levels.',
            'uz' => 'Qonda glyukoza miqdorining oshishi bilan tavsiflanadigan metabolik kasalliklar guruhi.',
         ],
         [
            'ru' => 'Сахарный диабет связан с недостаточной выработкой инсулина, нарушением его действия или сочетанием этих факторов.',
            'en' => 'Diabetes mellitus results from insufficient insulin production, impaired insulin action or a combination of both.',
            'uz' => 'Qandli diabet insulin yetarli ishlab chiqarilmasligi, uning ta’siri buzilishi yoki ikkala omil birgalikda mavjud bo‘lishi bilan bog‘liq.',
         ]
      );
      
      $terms[] = $term(
         6, 4,
         [
            'ru' => 'Гипергликемия',
            'en' => 'Hyperglycemia',
            'uz' => 'Giperglikemiya',
         ],
         [
            'ru' => 'giperglikemiya',
            'en' => 'hyperglycemia',
            'uz' => 'giperglikemiya',
         ],
         [
            'ru' => 'Повышенный уровень глюкозы в крови.',
            'en' => 'An abnormally elevated level of glucose in the blood.',
            'uz' => 'Qondagi glyukoza miqdorining me’yordan yuqori bo‘lishi.',
         ],
         [
            'ru' => 'Гипергликемия является одним из основных признаков сахарного диабета, но может наблюдаться и при других состояниях.',
            'en' => 'Hyperglycemia is a major feature of diabetes but can also occur in other conditions.',
            'uz' => 'Giperglikemiya qandli diabetning asosiy belgilaridan biri bo‘lib, boshqa holatlarda ham uchrashi mumkin.',
         ]
      );
      
      $terms[] = $term(
         6, 4,
         [
            'ru' => 'Гипогликемия',
            'en' => 'Hypoglycemia',
            'uz' => 'Gipoglikemiya',
         ],
         [
            'ru' => 'gipoglikemiya',
            'en' => 'hypoglycemia',
            'uz' => 'gipoglikemiya',
         ],
         [
            'ru' => 'Снижение концентрации глюкозы в крови ниже нормального уровня.',
            'en' => 'A decrease in blood glucose concentration below the normal range.',
            'uz' => 'Qondagi glyukoza miqdorining me’yoriy darajadan pasayishi.',
         ],
         [
            'ru' => 'Гипогликемия может сопровождаться потливостью, дрожью, слабостью, спутанностью сознания и другими симптомами.',
            'en' => 'Hypoglycemia may be associated with sweating, shaking, weakness, confusion and other symptoms.',
            'uz' => 'Gipoglikemiya terlash, titrash, holsizlik, ong chalkashishi va boshqa belgilar bilan kechishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         6, 7,
         [
            'ru' => 'Инсулин',
            'en' => 'Insulin',
            'uz' => 'Insulin',
         ],
         [
            'ru' => 'insulin',
            'en' => 'insulin',
            'uz' => 'insulin',
         ],
         [
            'ru' => 'Гормон поджелудочной железы, играющий ключевую роль в регуляции уровня глюкозы в крови.',
            'en' => 'A pancreatic hormone that plays a key role in regulating blood glucose levels.',
            'uz' => 'Qondagi glyukoza miqdorini boshqarishda muhim rol o‘ynaydigan oshqozon osti bezi gormoni.',
         ],
         [
            'ru' => 'Инсулин способствует поступлению глюкозы из крови в клетки и участвует в регулировании энергетического обмена.',
            'en' => 'Insulin helps glucose move from the blood into cells and participates in the regulation of energy metabolism.',
            'uz' => 'Insulin glyukozaning qondan hujayralarga o‘tishiga yordam beradi va energiya almashinuvini boshqarishda qatnashadi.',
         ]
      );
      $terms[] = $term(
         7, 1,
         [
            'ru' => 'Анемия',
            'en' => 'Anemia',
            'uz' => 'Anemiya',
         ],
         [
            'ru' => 'anemiya',
            'en' => 'anemia',
            'uz' => 'anemiya',
         ],
         [
            'ru' => 'Состояние, при котором количество эритроцитов или концентрация гемоглобина ниже нормального уровня.',
            'en' => 'A condition in which the number of red blood cells or the concentration of hemoglobin is below normal.',
            'uz' => 'Eritrotsitlar soni yoki gemoglobin miqdori me’yordan past bo‘ladigan holat.',
         ],
         [
            'ru' => 'При анемии способность крови переносить кислород может снижаться, что нередко приводит к слабости и утомляемости.',
            'en' => 'Anemia can reduce the oxygen-carrying capacity of the blood and may cause weakness and fatigue.',
            'uz' => 'Anemiyada qonning kislorod tashish qobiliyati kamayishi mumkin, bu holsizlik va charchoqqa olib kelishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         7, 4,
         [
            'ru' => 'Гемоглобин',
            'en' => 'Hemoglobin',
            'uz' => 'Gemoglobin',
         ],
         [
            'ru' => 'gemoglobin',
            'en' => 'hemoglobin',
            'uz' => 'gemoglobin',
         ],
         [
            'ru' => 'Белок эритроцитов, который связывает и переносит кислород.',
            'en' => 'A protein in red blood cells that binds and transports oxygen.',
            'uz' => 'Eritrotsitlar tarkibidagi kislorodni bog‘lab tashuvchi oqsil.',
         ],
         [
            'ru' => 'Основная функция гемоглобина заключается в переносе кислорода от лёгких к тканям организма.',
            'en' => 'The primary function of hemoglobin is to carry oxygen from the lungs to body tissues.',
            'uz' => 'Gemoglobinning asosiy vazifasi kislorodni o‘pkadan organizm to‘qimalariga tashishdir.',
         ]
      );
      
      $terms[] = $term(
         7, 3,
         [
            'ru' => 'Эритроцит',
            'en' => 'Erythrocyte',
            'uz' => 'Eritrotsit',
         ],
         [
            'ru' => 'eritrocit',
            'en' => 'erythrocyte',
            'uz' => 'eritrotsit',
         ],
         [
            'ru' => 'Красная клетка крови, основной функцией которой является транспорт кислорода.',
            'en' => 'A red blood cell whose main function is the transport of oxygen.',
            'uz' => 'Asosiy vazifasi kislorod tashish bo‘lgan qizil qon hujayrasi.',
         ],
         [
            'ru' => 'Эритроциты содержат гемоглобин и являются наиболее многочисленными форменными элементами крови.',
            'en' => 'Erythrocytes contain hemoglobin and are the most numerous formed elements of blood.',
            'uz' => 'Eritrotsitlar gemoglobinni o‘z ichiga oladi va qonning eng ko‘p sonli shaklli elementlaridir.',
         ]
      );
      
      $terms[] = $term(
         7, 3,
         [
            'ru' => 'Лейкоцит',
            'en' => 'Leukocyte',
            'uz' => 'Leykotsit',
         ],
         [
            'ru' => 'leykocit',
            'en' => 'leukocyte',
            'uz' => 'leykotsit',
         ],
         [
            'ru' => 'Белая клетка крови, участвующая в иммунной защите организма.',
            'en' => 'A white blood cell involved in the body’s immune defense.',
            'uz' => 'Organizmning immun himoyasida ishtirok etadigan oq qon hujayrasi.',
         ],
         [
            'ru' => 'Существует несколько типов лейкоцитов, выполняющих различные функции в иммунной системе.',
            'en' => 'Several types of leukocytes exist, each performing different functions in the immune system.',
            'uz' => 'Leykotsitlarning immun tizimida turli vazifalarni bajaruvchi bir necha turlari mavjud.',
         ]
      );
      
      $terms[] = $term(
         7, 3,
         [
            'ru' => 'Тромбоцит',
            'en' => 'Platelet',
            'uz' => 'Trombotsit',
         ],
         [
            'ru' => 'trombocit',
            'en' => 'platelet',
            'uz' => 'trombotsit',
         ],
         [
            'ru' => 'Форменный элемент крови, участвующий в остановке кровотечения и образовании кровяного сгустка.',
            'en' => 'A blood component involved in stopping bleeding and forming blood clots.',
            'uz' => 'Qon ketishini to‘xtatish va qon ivindisini hosil qilishda qatnashadigan qon elementi.',
         ],
         [
            'ru' => 'Тромбоциты играют важную роль в системе гемостаза и восстановлении повреждённых сосудов.',
            'en' => 'Platelets play an important role in hemostasis and the repair of damaged blood vessels.',
            'uz' => 'Trombotsitlar gemostaz tizimida va shikastlangan qon tomirlarini tiklashda muhim rol o‘ynaydi.',
         ]
      );
      
      $terms[] = $term(
         7, 1,
         [
            'ru' => 'Тромбоз',
            'en' => 'Thrombosis',
            'uz' => 'Tromboz',
         ],
         [
            'ru' => 'tromboz',
            'en' => 'thrombosis',
            'uz' => 'tromboz',
         ],
         [
            'ru' => 'Образование кровяного сгустка — тромба — внутри кровеносного сосуда.',
            'en' => 'The formation of a blood clot, or thrombus, within a blood vessel.',
            'uz' => 'Qon tomiri ichida tromb deb ataluvchi qon ivindisining hosil bo‘lishi.',
         ],
         [
            'ru' => 'Тромб способен частично или полностью нарушать кровоток по поражённому сосуду.',
            'en' => 'A thrombus can partially or completely obstruct blood flow through the affected vessel.',
            'uz' => 'Tromb zararlangan qon tomirida qon oqimini qisman yoki to‘liq to‘sib qo‘yishi mumkin.',
         ]
      );
      $terms[] = $term(
         5, 1,
         [
            'ru' => 'Гастрит',
            'en' => 'Gastritis',
            'uz' => 'Gastrit',
         ],
         [
            'ru' => 'gastrit',
            'en' => 'gastritis',
            'uz' => 'gastrit',
         ],
         [
            'ru' => 'Воспаление слизистой оболочки желудка.',
            'en' => 'Inflammation of the lining of the stomach.',
            'uz' => 'Oshqozon shilliq qavatining yallig‘lanishi.',
         ],
         [
            'ru' => 'Гастрит может иметь острое или хроническое течение и возникать по различным причинам.',
            'en' => 'Gastritis may be acute or chronic and can develop for various reasons.',
            'uz' => 'Gastrit o‘tkir yoki surunkali kechishi hamda turli sabablarga ko‘ra rivojlanishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         5, 1,
         [
            'ru' => 'Гепатит',
            'en' => 'Hepatitis',
            'uz' => 'Gepatit',
         ],
         [
            'ru' => 'gepatit',
            'en' => 'hepatitis',
            'uz' => 'gepatit',
         ],
         [
            'ru' => 'Воспаление ткани печени.',
            'en' => 'Inflammation of liver tissue.',
            'uz' => 'Jigar to‘qimasining yallig‘lanishi.',
         ],
         [
            'ru' => 'Гепатит может иметь инфекционную, токсическую, аутоиммунную и другую природу.',
            'en' => 'Hepatitis may have infectious, toxic, autoimmune or other causes.',
            'uz' => 'Gepatit infeksion, toksik, autoimmun va boshqa sabablarga ega bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         5, 1,
         [
            'ru' => 'Цирроз печени',
            'en' => 'Liver cirrhosis',
            'uz' => 'Jigar sirrozi',
         ],
         [
            'ru' => 'cirroz-pecheni',
            'en' => 'liver-cirrhosis',
            'uz' => 'jigar-sirrozi',
         ],
         [
            'ru' => 'Хроническое заболевание печени, при котором нормальная ткань постепенно замещается фиброзной.',
            'en' => 'A chronic liver disease in which normal liver tissue is progressively replaced by fibrotic tissue.',
            'uz' => 'Normal jigar to‘qimasi asta-sekin fibroz to‘qima bilan almashinadigan surunkali jigar kasalligi.',
         ],
         [
            'ru' => 'Выраженное рубцевание печени нарушает её структуру и способность выполнять нормальные функции.',
            'en' => 'Extensive liver scarring disrupts its structure and ability to perform normal functions.',
            'uz' => 'Jigarning kuchli chandiqlanishi uning tuzilishi va normal funksiyalarini bajarish qobiliyatini buzadi.',
         ]
      );
      
      $terms[] = $term(
         5, 1,
         [
            'ru' => 'Панкреатит',
            'en' => 'Pancreatitis',
            'uz' => 'Pankreatit',
         ],
         [
            'ru' => 'pankreatit',
            'en' => 'pancreatitis',
            'uz' => 'pankreatit',
         ],
         [
            'ru' => 'Воспаление поджелудочной железы.',
            'en' => 'Inflammation of the pancreas.',
            'uz' => 'Oshqozon osti bezining yallig‘lanishi.',
         ],
         [
            'ru' => 'Панкреатит может протекать в острой или хронической форме и нарушать пищеварительную и гормональную функции железы.',
            'en' => 'Pancreatitis may be acute or chronic and can impair the digestive and hormonal functions of the pancreas.',
            'uz' => 'Pankreatit o‘tkir yoki surunkali kechib, bezning hazm qilish va gormonal funksiyalarini buzishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         5, 2,
         [
            'ru' => 'Желтуха',
            'en' => 'Jaundice',
            'uz' => 'Sariqlik',
         ],
         [
            'ru' => 'zheltuha',
            'en' => 'jaundice',
            'uz' => 'sariqlik',
         ],
         [
            'ru' => 'Жёлтое окрашивание кожи и слизистых оболочек, связанное с повышением уровня билирубина.',
            'en' => 'Yellow discoloration of the skin and mucous membranes associated with elevated bilirubin.',
            'uz' => 'Bilirubin miqdori oshishi bilan bog‘liq teri va shilliq qavatlarning sarg‘ayishi.',
         ],
         [
            'ru' => 'Желтуха является признаком, а не самостоятельным диагнозом, и может возникать при различных заболеваниях.',
            'en' => 'Jaundice is a sign rather than a diagnosis itself and may occur in a variety of diseases.',
            'uz' => 'Sariqlik mustaqil tashxis emas, balki turli kasalliklarda uchrashi mumkin bo‘lgan belgidir.',
         ]
      );
      $terms[] = $term(
         8, 7,
         [
            'ru' => 'Инфекция',
            'en' => 'Infection',
            'uz' => 'Infeksiya',
         ],
         [
            'ru' => 'infekciya',
            'en' => 'infection',
            'uz' => 'infeksiya',
         ],
         [
            'ru' => 'Проникновение и размножение инфекционного агента в организме.',
            'en' => 'The invasion and multiplication of an infectious agent within the body.',
            'uz' => 'Infeksion agentning organizmga kirishi va unda ko‘payishi.',
         ],
         [
            'ru' => 'Инфекционными агентами могут быть бактерии, вирусы, грибы, паразиты и другие микроорганизмы.',
            'en' => 'Infectious agents can include bacteria, viruses, fungi, parasites and other microorganisms.',
            'uz' => 'Infeksion agentlarga bakteriyalar, viruslar, zamburug‘lar, parazitlar va boshqa mikroorganizmlar kiradi.',
         ]
      );
      
      $terms[] = $term(
         8, 1,
         [
            'ru' => 'Сепсис',
            'en' => 'Sepsis',
            'uz' => 'Sepsis',
         ],
         [
            'ru' => 'sepsis',
            'en' => 'sepsis',
            'uz' => 'sepsis',
         ],
         [
            'ru' => 'Опасное для жизни состояние, возникающее вследствие нарушенной реакции организма на инфекцию.',
            'en' => 'A life-threatening condition caused by a dysregulated response of the body to infection.',
            'uz' => 'Organizmning infeksiyaga buzilgan javobi natijasida yuzaga keladigan hayot uchun xavfli holat.',
         ],
         [
            'ru' => 'При сепсисе реакция организма на инфекцию может приводить к повреждению собственных органов и тканей.',
            'en' => 'In sepsis, the body’s response to infection can lead to damage to its own organs and tissues.',
            'uz' => 'Sepsisda organizmning infeksiyaga javobi o‘z a’zolari va to‘qimalarining shikastlanishiga olib kelishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         1, 2,
         [
            'ru' => 'Лихорадка',
            'en' => 'Fever',
            'uz' => 'Isitma',
         ],
         [
            'ru' => 'lihoradka',
            'en' => 'fever',
            'uz' => 'isitma',
         ],
         [
            'ru' => 'Повышение температуры тела выше обычного физиологического диапазона.',
            'en' => 'An elevation of body temperature above its usual physiological range.',
            'uz' => 'Tana haroratining odatiy fiziologik darajadan yuqori ko‘tarilishi.',
         ],
         [
            'ru' => 'Лихорадка часто является частью защитной реакции организма на инфекцию или воспаление.',
            'en' => 'Fever is commonly part of the body’s response to infection or inflammation.',
            'uz' => 'Isitma ko‘pincha organizmning infeksiya yoki yallig‘lanishga javob reaksiyasining bir qismidir.',
         ]
      );
      
      $terms[] = $term(
         1, 7,
         [
            'ru' => 'Воспаление',
            'en' => 'Inflammation',
            'uz' => 'Yallig‘lanish',
         ],
         [
            'ru' => 'vospalenie',
            'en' => 'inflammation',
            'uz' => 'yalliglanish',
         ],
         [
            'ru' => 'Защитная реакция организма на повреждение, инфекцию или другое неблагоприятное воздействие.',
            'en' => 'A protective biological response to injury, infection or another harmful stimulus.',
            'uz' => 'Organizmning shikastlanish, infeksiya yoki boshqa zararli ta’sirga himoya reaksiyasi.',
         ],
         [
            'ru' => 'Классическими признаками воспаления являются покраснение, отёк, повышение температуры, боль и нарушение функции.',
            'en' => 'Classic signs of inflammation include redness, swelling, heat, pain and impaired function.',
            'uz' => 'Yallig‘lanishning klassik belgilariga qizarish, shish, issiqlik, og‘riq va funksiya buzilishi kiradi.',
         ]
      );
      
      $terms[] = $term(
         1, 2,
         [
            'ru' => 'Отёк',
            'en' => 'Edema',
            'uz' => 'Shish',
         ],
         [
            'ru' => 'otek',
            'en' => 'edema',
            'uz' => 'shish',
         ],
         [
            'ru' => 'Избыточное накопление жидкости в тканях организма.',
            'en' => 'Excess accumulation of fluid within body tissues.',
            'uz' => 'Organizm to‘qimalarida suyuqlikning ortiqcha to‘planishi.',
         ],
         [
            'ru' => 'Отёк может быть локальным или распространённым и встречается при различных заболеваниях и состояниях.',
            'en' => 'Edema may be localized or widespread and can occur in many different conditions.',
            'uz' => 'Shish mahalliy yoki keng tarqalgan bo‘lishi va turli kasallik hamda holatlarda uchrashi mumkin.',
         ]
      );
      
      $terms[] = $term(
         1, 1,
         [
            'ru' => 'Обезвоживание',
            'en' => 'Dehydration',
            'uz' => 'Suvsizlanish',
         ],
         [
            'ru' => 'obezvozhivanie',
            'en' => 'dehydration',
            'uz' => 'suvsizlanish',
         ],
         [
            'ru' => 'Состояние, при котором организм теряет больше жидкости, чем получает.',
            'en' => 'A condition in which the body loses more fluid than it takes in.',
            'uz' => 'Organizm qabul qilganidan ko‘proq suyuqlik yo‘qotadigan holat.',
         ],
         [
            'ru' => 'Недостаток жидкости нарушает водно-электролитный баланс и может влиять на работу органов.',
            'en' => 'Insufficient body fluid disrupts fluid and electrolyte balance and can affect organ function.',
            'uz' => 'Suyuqlik yetishmovchiligi suv-elektrolit muvozanatini buzib, a’zolar faoliyatiga ta’sir qilishi mumkin.',
         ]
      );
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Артериальное давление',
            'en' => 'Blood pressure',
            'uz' => 'Arterial qon bosimi',
         ],
         [
            'ru' => 'arterialnoe-davlenie',
            'en' => 'blood-pressure',
            'uz' => 'arterial-qon-bosimi',
         ],
         [
            'ru' => 'Давление, которое кровь оказывает на стенки артерий.',
            'en' => 'The pressure exerted by circulating blood against the walls of the arteries.',
            'uz' => 'Qonning arteriya devorlariga ko‘rsatadigan bosimi.',
         ],
         [
            'ru' => 'Артериальное давление обычно записывают двумя значениями: систолическим и диастолическим.',
            'en' => 'Blood pressure is generally expressed using systolic and diastolic values.',
            'uz' => 'Arterial qon bosimi odatda sistolik va diastolik ikki ko‘rsatkich bilan ifodalanadi.',
         ]
      );
      
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Пульс',
            'en' => 'Pulse',
            'uz' => 'Puls',
         ],
         [
            'ru' => 'puls',
            'en' => 'pulse',
            'uz' => 'puls',
         ],
         [
            'ru' => 'Ритмические колебания стенок артерий, связанные с сокращениями сердца.',
            'en' => 'Rhythmic expansion of the arteries associated with contractions of the heart.',
            'uz' => 'Yurak qisqarishlari bilan bog‘liq arteriya devorlarining ritmik tebranishlari.',
         ],
         [
            'ru' => 'Оценка пульса позволяет получить информацию о частоте и ритме сердечных сокращений.',
            'en' => 'Pulse assessment provides information about the rate and rhythm of the heartbeat.',
            'uz' => 'Pulsni baholash yurak urish tezligi va ritmi haqida ma’lumot beradi.',
         ]
      );
      
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Электрокардиография',
            'en' => 'Electrocardiography',
            'uz' => 'Elektrokardiografiya',
         ],
         [
            'ru' => 'elektrokardiografiya',
            'en' => 'electrocardiography',
            'uz' => 'elektrokardiografiya',
         ],
         [
            'ru' => 'Метод регистрации электрической активности сердца.',
            'en' => 'A method used to record the electrical activity of the heart.',
            'uz' => 'Yurakning elektr faolligini qayd etish usuli.',
         ],
         [
            'ru' => 'Результат исследования называется электрокардиограммой — ЭКГ.',
            'en' => 'The resulting recording is called an electrocardiogram, or ECG.',
            'uz' => 'Tekshiruv natijasi elektrokardiogramma yoki EKG deb ataladi.',
         ]
      );
      
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Ультразвуковое исследование',
            'en' => 'Ultrasound',
            'uz' => 'Ultratovush tekshiruvi',
         ],
         [
            'ru' => 'ultrazvukovoe-issledovanie',
            'en' => 'ultrasound',
            'uz' => 'ultratovush-tekshiruvi',
         ],
         [
            'ru' => 'Диагностический метод визуализации внутренних органов и тканей с помощью ультразвуковых волн.',
            'en' => 'A diagnostic imaging method that uses ultrasound waves to visualize internal organs and tissues.',
            'uz' => 'Ichki a’zolar va to‘qimalarni ultratovush to‘lqinlari yordamida tasvirlash diagnostik usuli.',
         ],
         [
            'ru' => 'УЗИ широко используется для исследования органов брюшной полости, сердца, сосудов и других структур.',
            'en' => 'Ultrasound is widely used to examine abdominal organs, the heart, blood vessels and many other structures.',
            'uz' => 'UTT qorin bo‘shlig‘i a’zolari, yurak, qon tomirlari va boshqa tuzilmalarni tekshirishda keng qo‘llaniladi.',
         ]
      );
      
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Компьютерная томография',
            'en' => 'Computed tomography',
            'uz' => 'Kompyuter tomografiyasi',
         ],
         [
            'ru' => 'kompyuternaya-tomografiya',
            'en' => 'computed-tomography',
            'uz' => 'kompyuter-tomografiyasi',
         ],
         [
            'ru' => 'Метод медицинской визуализации, использующий рентгеновское излучение и компьютерную обработку данных.',
            'en' => 'A medical imaging technique using X-rays and computer processing to create detailed images.',
            'uz' => 'Rentgen nurlari va kompyuter yordamida ma’lumotlarni qayta ishlash orqali batafsil tasvirlar yaratadigan diagnostik usul.',
         ],
         [
            'ru' => 'КТ позволяет получать послойные изображения внутренних органов и анатомических структур.',
            'en' => 'CT produces cross-sectional images of internal organs and anatomical structures.',
            'uz' => 'KT ichki a’zolar va anatomik tuzilmalarning qatlamli tasvirlarini olish imkonini beradi.',
         ]
      );
      
      $terms[] = $term(
         9, 4,
         [
            'ru' => 'Магнитно-резонансная томография',
            'en' => 'Magnetic resonance imaging',
            'uz' => 'Magnit-rezonans tomografiya',
         ],
         [
            'ru' => 'magnitno-rezonansnaya-tomografiya',
            'en' => 'magnetic-resonance-imaging',
            'uz' => 'magnit-rezonans-tomografiya',
         ],
         [
            'ru' => 'Метод медицинской визуализации, использующий магнитное поле и радиочастотные сигналы.',
            'en' => 'A medical imaging technique that uses magnetic fields and radiofrequency signals.',
            'uz' => 'Magnit maydon va radiochastota signallaridan foydalanadigan tibbiy tasvirlash usuli.',
         ],
         [
            'ru' => 'МРТ позволяет получать детальные изображения мягких тканей, головного мозга, суставов и других структур.',
            'en' => 'MRI provides detailed images of soft tissues, the brain, joints and other structures.',
            'uz' => 'MRT yumshoq to‘qimalar, miya, bo‘g‘imlar va boshqa tuzilmalarning batafsil tasvirlarini olish imkonini beradi.',
         ]
      );
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Антибиотик',
            'en' => 'Antibiotic',
            'uz' => 'Antibiotik',
         ],
         [
            'ru' => 'antibiotik',
            'en' => 'antibiotic',
            'uz' => 'antibiotik',
         ],
         [
            'ru' => 'Лекарственное средство, применяемое для лечения определённых бактериальных инфекций.',
            'en' => 'A medicine used to treat certain bacterial infections.',
            'uz' => 'Ayrim bakterial infeksiyalarni davolash uchun qo‘llaniladigan dori vositasi.',
         ],
         [
            'ru' => 'Антибиотики воздействуют на бактерии и не предназначены для лечения большинства вирусных инфекций.',
            'en' => 'Antibiotics act against bacteria and are not used to treat most viral infections.',
            'uz' => 'Antibiotiklar bakteriyalarga qarshi ta’sir qiladi va aksariyat virusli infeksiyalarni davolash uchun mo‘ljallanmagan.',
         ]
      );
      
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Анальгетик',
            'en' => 'Analgesic',
            'uz' => 'Analgetik',
         ],
         [
            'ru' => 'analgetik',
            'en' => 'analgesic',
            'uz' => 'analgetik',
         ],
         [
            'ru' => 'Лекарственное средство, предназначенное для уменьшения или устранения боли.',
            'en' => 'A medication used to reduce or relieve pain.',
            'uz' => 'Og‘riqni kamaytirish yoki bartaraf etish uchun qo‘llaniladigan dori vositasi.',
         ],
         [
            'ru' => 'Анальгетики включают различные группы препаратов с разными механизмами действия.',
            'en' => 'Analgesics include several groups of medicines with different mechanisms of action.',
            'uz' => 'Analgetiklar turli ta’sir mexanizmlariga ega bo‘lgan bir necha dori guruhlarini o‘z ichiga oladi.',
         ]
      );
      
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Антикоагулянт',
            'en' => 'Anticoagulant',
            'uz' => 'Antikoagulyant',
         ],
         [
            'ru' => 'antikoagulyant',
            'en' => 'anticoagulant',
            'uz' => 'antikoagulyant',
         ],
         [
            'ru' => 'Препарат, снижающий способность крови образовывать тромбы.',
            'en' => 'A medicine that reduces the blood’s ability to form clots.',
            'uz' => 'Qonning tromb hosil qilish qobiliyatini kamaytiradigan dori vositasi.',
         ],
         [
            'ru' => 'Антикоагулянты воздействуют на систему свёртывания крови и используются при определённых рисках тромбоза.',
            'en' => 'Anticoagulants act on the blood coagulation system and are used in situations involving certain risks of thrombosis.',
            'uz' => 'Antikoagulyantlar qon ivish tizimiga ta’sir qiladi va ayrim tromboz xavflarida qo‘llaniladi.',
         ]
      );
      
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Дозировка',
            'en' => 'Dosage',
            'uz' => 'Dozalash',
         ],
         [
            'ru' => 'dozirovka',
            'en' => 'dosage',
            'uz' => 'dozalash',
         ],
         [
            'ru' => 'Количество лекарственного средства и режим его применения.',
            'en' => 'The amount of a medicine and the schedule according to which it is administered.',
            'uz' => 'Dori vositasining miqdori va uni qo‘llash tartibi.',
         ],
         [
            'ru' => 'Дозировка может учитывать разовую дозу, частоту применения, продолжительность курса и особенности пациента.',
            'en' => 'Dosage may include the individual dose, frequency of administration, duration and patient-specific factors.',
            'uz' => 'Dozalash bir martalik doza, qabul qilish chastotasi, davomiyligi va bemorning individual xususiyatlarini hisobga olishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Противопоказание',
            'en' => 'Contraindication',
            'uz' => 'Qarshi ko‘rsatma',
         ],
         [
            'ru' => 'protivopokazanie',
            'en' => 'contraindication',
            'uz' => 'qarshi-korsatma',
         ],
         [
            'ru' => 'Состояние или фактор, при котором применение определённого метода лечения или препарата нежелательно либо недопустимо.',
            'en' => 'A condition or factor that makes a particular treatment or medicine inadvisable or inappropriate.',
            'uz' => 'Muayyan davolash usuli yoki dori vositasini qo‘llash tavsiya etilmaydigan yoki mumkin bo‘lmagan holat yoki omil.',
         ],
         [
            'ru' => 'Противопоказания могут быть абсолютными или относительными в зависимости от клинической ситуации.',
            'en' => 'Contraindications may be absolute or relative depending on the clinical situation.',
            'uz' => 'Qarshi ko‘rsatmalar klinik vaziyatga qarab mutlaq yoki nisbiy bo‘lishi mumkin.',
         ]
      );
      
      $terms[] = $term(
         10, 6,
         [
            'ru' => 'Побочный эффект',
            'en' => 'Adverse effect',
            'uz' => 'Nojo‘ya ta’sir',
         ],
         [
            'ru' => 'pobochnyy-effekt',
            'en' => 'adverse-effect',
            'uz' => 'nojoya-tasir',
         ],
         [
            'ru' => 'Нежелательная реакция, возникающая при применении лекарственного средства.',
            'en' => 'An unwanted effect or reaction associated with the use of a medicine.',
            'uz' => 'Dori vositasini qo‘llash bilan bog‘liq yuzaga keladigan istalmagan ta’sir yoki reaksiya.',
         ],
         [
            'ru' => 'Побочные эффекты различаются по частоте, выраженности и клинической значимости.',
            'en' => 'Adverse effects vary in frequency, severity and clinical significance.',
            'uz' => 'Nojo‘ya ta’sirlar uchrash chastotasi, og‘irligi va klinik ahamiyatiga ko‘ra farqlanadi.',
         ]
      );
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Артерия',
            'en' => 'Artery',
            'uz' => 'Arteriya',
         ],
         [
            'ru' => 'arteriya',
            'en' => 'artery',
            'uz' => 'arteriya',
         ],
         [
            'ru' => 'Кровеносный сосуд, по которому кровь движется от сердца к органам и тканям.',
            'en' => 'A blood vessel that carries blood away from the heart toward organs and tissues.',
            'uz' => 'Qonni yurakdan a’zolar va to‘qimalarga olib boruvchi qon tomiri.',
         ],
         [
            'ru' => 'Артерии являются важной частью сердечно-сосудистой системы и постепенно разветвляются на более мелкие сосуды.',
            'en' => 'Arteries are an important part of the cardiovascular system and progressively branch into smaller vessels.',
            'uz' => 'Arteriyalar yurak-qon tomir tizimining muhim qismi bo‘lib, asta-sekin kichikroq tomirlarga tarmoqlanadi.',
         ]
      );
      
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Вена',
            'en' => 'Vein',
            'uz' => 'Vena',
         ],
         [
            'ru' => 'vena',
            'en' => 'vein',
            'uz' => 'vena',
         ],
         [
            'ru' => 'Кровеносный сосуд, по которому кровь обычно возвращается к сердцу.',
            'en' => 'A blood vessel that generally carries blood back toward the heart.',
            'uz' => 'Qonni odatda yurakka qaytarib olib keluvchi qon tomiri.',
         ],
         [
            'ru' => 'Многие вены имеют клапаны, помогающие поддерживать движение крови в направлении сердца.',
            'en' => 'Many veins contain valves that help maintain blood flow toward the heart.',
            'uz' => 'Ko‘plab venalarda qonning yurak tomon harakatlanishiga yordam beruvchi klapanlar mavjud.',
         ]
      );
      
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Капилляр',
            'en' => 'Capillary',
            'uz' => 'Kapillyar',
         ],
         [
            'ru' => 'kapillyar',
            'en' => 'capillary',
            'uz' => 'kapillyar',
         ],
         [
            'ru' => 'Мельчайший кровеносный сосуд, соединяющий артериальную и венозную части кровообращения.',
            'en' => 'A very small blood vessel connecting the arterial and venous sides of circulation.',
            'uz' => 'Qon aylanishining arterial va venoz qismlarini bog‘lovchi eng mayda qon tomiri.',
         ],
         [
            'ru' => 'Через стенки капилляров происходит обмен кислорода, питательных веществ и продуктов обмена между кровью и тканями.',
            'en' => 'Capillary walls allow the exchange of oxygen, nutrients and metabolic products between blood and tissues.',
            'uz' => 'Kapillyar devorlari orqali qon va to‘qimalar o‘rtasida kislorod, oziq moddalar va almashinuv mahsulotlari almashadi.',
         ]
      );
      
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Миокард',
            'en' => 'Myocardium',
            'uz' => 'Miokard',
         ],
         [
            'ru' => 'miokard',
            'en' => 'myocardium',
            'uz' => 'miokard',
         ],
         [
            'ru' => 'Мышечный слой стенки сердца, обеспечивающий его сокращения.',
            'en' => 'The muscular layer of the heart wall responsible for its contractions.',
            'uz' => 'Yurak devorining qisqarishni ta’minlaydigan mushak qatlami.',
         ],
         [
            'ru' => 'Сокращения миокарда создают силу, необходимую для движения крови по системе кровообращения.',
            'en' => 'Contractions of the myocardium generate the force needed to move blood through the circulatory system.',
            'uz' => 'Miokard qisqarishi qonni qon aylanish tizimi bo‘ylab harakatlantirish uchun zarur kuchni hosil qiladi.',
         ]
      );
      
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Нейрон',
            'en' => 'Neuron',
            'uz' => 'Neyron',
         ],
         [
            'ru' => 'neyron',
            'en' => 'neuron',
            'uz' => 'neyron',
         ],
         [
            'ru' => 'Специализированная клетка нервной системы, способная принимать, обрабатывать и передавать сигналы.',
            'en' => 'A specialized cell of the nervous system capable of receiving, processing and transmitting signals.',
            'uz' => 'Signallarni qabul qilish, qayta ishlash va uzatishga moslashgan nerv tizimining maxsus hujayrasi.',
         ],
         [
            'ru' => 'Нейроны взаимодействуют друг с другом и другими клетками через электрические и химические сигналы.',
            'en' => 'Neurons communicate with one another and other cells through electrical and chemical signals.',
            'uz' => 'Neyronlar bir-biri va boshqa hujayralar bilan elektr hamda kimyoviy signallar orqali aloqa qiladi.',
         ]
      );
      
      $terms[] = $term(
         11, 3,
         [
            'ru' => 'Альвеола',
            'en' => 'Alveolus',
            'uz' => 'Alveola',
         ],
         [
            'ru' => 'alveola',
            'en' => 'alveolus',
            'uz' => 'alveola',
         ],
         [
            'ru' => 'Мелкий воздушный пузырёк лёгкого, в котором происходит газообмен между воздухом и кровью.',
            'en' => 'A tiny air sac in the lung where gas exchange between air and blood occurs.',
            'uz' => 'Havo va qon o‘rtasida gaz almashinuvi sodir bo‘ladigan o‘pkadagi mayda havo xaltachasi.',
         ],
         [
            'ru' => 'Альвеолы окружены сетью капилляров и обеспечивают поступление кислорода в кровь и удаление углекислого газа.',
            'en' => 'Alveoli are surrounded by capillaries and allow oxygen to enter the blood while carbon dioxide is removed.',
            'uz' => 'Alveolalar kapillyarlar tarmog‘i bilan o‘ralgan bo‘lib, kislorodning qonga o‘tishi va karbonat angidrid chiqarilishini ta’minlaydi.',
         ]
      );
      $terms[] = $term(
         12, 1,
         [
            'ru' => 'Анафилаксия',
            'en' => 'Anaphylaxis',
            'uz' => 'Anafilaksiya',
         ],
         [
            'ru' => 'anafilaksiya',
            'en' => 'anaphylaxis',
            'uz' => 'anafilaksiya',
         ],
         [
            'ru' => 'Тяжёлая системная аллергическая реакция с быстрым развитием симптомов.',
            'en' => 'A severe systemic allergic reaction with rapid onset of symptoms.',
            'uz' => 'Belgilar tez rivojlanadigan og‘ir tizimli allergik reaksiya.',
         ],
         [
            'ru' => 'Анафилаксия способна затрагивать дыхательные пути, кровообращение, кожу и другие системы организма и является неотложным состоянием.',
            'en' => 'Anaphylaxis can affect the airways, circulation, skin and other body systems and is a medical emergency.',
            'uz' => 'Anafilaksiya nafas yo‘llari, qon aylanishi, teri va boshqa tizimlarga ta’sir qilishi mumkin hamda shoshilinch tibbiy holat hisoblanadi.',
         ]
      );
      
      $terms[] = $term(
         12, 1,
         [
            'ru' => 'Остановка сердца',
            'en' => 'Cardiac arrest',
            'uz' => 'Yurak to‘xtashi',
         ],
         [
            'ru' => 'ostanovka-serdca',
            'en' => 'cardiac-arrest',
            'uz' => 'yurak-toxtashi',
         ],
         [
            'ru' => 'Внезапное прекращение эффективной насосной деятельности сердца и кровообращения.',
            'en' => 'The sudden cessation of effective pumping activity of the heart and circulation.',
            'uz' => 'Yurakning samarali nasos faoliyati va qon aylanishining to‘satdan to‘xtashi.',
         ],
         [
            'ru' => 'Остановка сердца представляет непосредственную угрозу жизни и требует немедленной экстренной помощи.',
            'en' => 'Cardiac arrest is immediately life-threatening and requires urgent emergency intervention.',
            'uz' => 'Yurak to‘xtashi hayot uchun bevosita xavf tug‘diradi va zudlik bilan shoshilinch yordamni talab qiladi.',
         ]
      );
      
      $terms[] = $term(
         12, 5,
         [
            'ru' => 'Сердечно-лёгочная реанимация',
            'en' => 'Cardiopulmonary resuscitation',
            'uz' => 'Yurak-o‘pka reanimatsiyasi',
         ],
         [
            'ru' => 'serdechno-legochnaya-reanimaciya',
            'en' => 'cardiopulmonary-resuscitation',
            'uz' => 'yurak-opka-reanimatsiyasi',
         ],
         [
            'ru' => 'Комплекс экстренных мероприятий для поддержания кровообращения и дыхания при остановке сердца.',
            'en' => 'A set of emergency measures used to maintain circulation and breathing during cardiac arrest.',
            'uz' => 'Yurak to‘xtaganda qon aylanishi va nafasni saqlash uchun bajariladigan shoshilinch choralar majmuasi.',
         ],
         [
            'ru' => 'Сердечно-лёгочная реанимация обычно включает компрессии грудной клетки и другие мероприятия в соответствии с алгоритмами реанимационной помощи.',
            'en' => 'Cardiopulmonary resuscitation generally includes chest compressions and other measures according to resuscitation guidelines.',
            'uz' => 'Yurak-o‘pka reanimatsiyasi odatda ko‘krak qafasini bosish va reanimatsiya algoritmlariga muvofiq boshqa choralarni o‘z ichiga oladi.',
         ]
      );
      
      $this->batchInsert(
         '{{%medical_dictionary}}',
         [
            'category_id',
            'type',
            
            'name_ru',
            'name_en',
            'name_uz',
            
            'slug_ru',
            'slug_en',
            'slug_uz',
            
            'desc_ru',
            'desc_en',
            'desc_uz',
            
            'content_ru',
            'content_en',
            'content_uz',
            
            'seo_title_ru',
            'seo_title_en',
            'seo_title_uz',
            
            'seo_desc_ru',
            'seo_desc_en',
            'seo_desc_uz',
            
            'created_at',
            'updated_at',
            'status',
         ],
         $terms
      );
   }
   
   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
      $this->dropTable('{{%medical_dictionary}}');
   }
   
}
