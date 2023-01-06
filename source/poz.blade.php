@extends('__source.layouts.master', ['force_title' => 'POZ Włącznik'])

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-indigo-600 dark:text-purple-300 mb-0">
            <span class="align-middle">POZ Włącznik</span>
        </h1>
        <p class="text-justify text-l text-medium font-semibold font-heading tracking-wider">
            Projekt POZ Włącznik był realizowany w <b>kiedy-do-kiedy</b> i-opis przy wsparciu z grantu od-kogo Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.
        </p>
        <p class="text-justify text-l text-medium font-semibold font-heading tracking-wider">
            W ramach projektu wydrukowane zostało <b>1212</b> podręczników i <b>122112</b> ulotek które trafiły do <b>212112</b> lekarzy POZ, zrealizowano <b>2121</b> szkoleń w miastach takich jak <b>[miast]</b> i przeszkolono <b>94934934</b> osób
        </p>
        <p class="text-justify text-l text-medium font-semibold font-heading tracking-wider mb-0">
            od dziś udostępniamy <a href="#link">podręcznik</a> oraz <a href="#link2">ulotkę</a> w formatach PDF dla wszystkich zainteresowanych samodzieloną dystrubucją osób
        </p>
        <h2 class="mt-12 mb-4 text-indigo-600 dark:text-purple-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                <path d="m10 17-5.5-2.75v-5L1 7.5 10 3l9 4.5V14h-1.5V8.25l-2 1v5Zm0-6.688L15.646 7.5 10 4.688 4.354 7.5Zm0 5 4-2V10l-4 2-4-2v3.312Zm0-5ZM10 12Zm0 0Z"></path>
            </svg>
            Przeszkolone przychodnie
        </h2>
        <ol class="text-justify inline-block">
            <li><b>NZOZ Przychodnia Medycyny Rodzinnej</b><br><i>Błogosławionego Wincentego Kadłubka 10-11, 71-450 Szczecin</i></li>
            <li><b>NZOZ Przychodnia Medycyny Rodzinnej</b><br><i>Fryderyka Chopina 22, 71-450 Szczecin</i></li>
            <li><b>Przychodnia Szczecińska</b><br><i>Fryderyka Chopina 22, 71-450 Szczecin</i></li>
            <li><b>Przykliniczna Przychodnia Specjalistyczna</b><br><i>Jaczewskiego 8, 20-954 Lublin</i></li>
            <li><b>Przychodnia Koralowa</b><br><i>Koralowa 29, 20-538 Lublin</i></li>
        </ol>
        <h2 class="mt-12 mb-4 text-indigo-600 dark:text-purple-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                <path d="m7.312 18.271-1.52-2.521-2.875-.646.271-2.916L1.25 10l1.938-2.188-.271-2.916 2.875-.646 1.52-2.521L10 2.896l2.688-1.167 1.52 2.521 2.875.646-.271 2.916L18.75 10l-1.938 2.188.271 2.916-2.875.646-1.52 2.521L10 17.104Zm1.646-5.375 4.834-4.813-1-.979-3.834 3.813-1.75-1.75-1 1Z"></path>
            </svg>
            Podziel się opinią
        </h2>
        <div class="alert text-justify w-full md:w-4/6 m-auto p-8">
            <p class="text-center"><b>Byłxś w przeszkolonej przez nas przychodni i chcesz podzielić się opinią? Wypełnij ankietę!</b></p>
            <form id='poz-survey'>
                <div class="relative">
                    <p id="response-message-survey" class="hidden text-red-600 dark:text-red-300 font-bold m-3 ml-0 block text-sm leading-5 mb-4">Komunikat</p>
                </div>
                <div class="relative">
                    <input type="text" name="firstNameSurvey" id="firstNameSurvey" class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" placeholder="Imię" required>
                    <label for="firstNameSurvey" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Imię
                    </label>
                </div>
                <div class="relative mt-4">
                    <input type="email" name="emailSurvey" id="emailSurvey" class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" placeholder="Adres e-mail" required>
                    <label for="emailSurvey" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Adres e-mail
                    </label>
                </div>
                <div class="relative mt-4">
                    <select class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" id="placeSurvey" name="placeSurvey" id="placeSurvey" rewind>
                        <option value="1">NZOZ Przychodnia Medycyny Rodzinnej (Błogosławionego Wincentego Kadłubka 10-11, 71-450 Szczecin)</option>
                        <option value="2">NZOZ Przychodnia Medycyny Rodzinnej (Fryderyka Chopina 22, 71-450 Szczecin)</option>
                        <option value="3">Przychodnia Szczecińska (Fryderyka Chopina 22, 71-450 Szczecin)</option>
                        <option value="4">Przykliniczna Przychodnia Specjalistyczna (Jaczewskiego 8, 20-954 Lublin<)</option>
                        <option value="5">Przychodnia Koralowa (Koralowa 29, 20-538 Lublin)</option>
                    </select>
                    <label for="placeSurvey" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Przychodnia
                    </label>
                </div>
                <div class="relative mt-4">
                    <div class="radio-stars w-full">
                        <label class="block md:hidden font-medium text-sm leading-5">
                            Jak oceniasz przygotowanie przychodni do przyjęcia osoby transpłciowej?
                        </label>
                        <div class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 text-gray-400 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                            <div class="rotate-rating inline-block">
                                <input type="radio" id="rating-1" name="ratingSurvey" value="5" /><label for="rating-1"></label>
                                <input type="radio" id="rating-2" name="ratingSurvey" value="4" /><label for="rating-2"></label>
                                <input type="radio" id="rating-3" name="ratingSurvey" value="3" /><label for="rating-3"></label>
                                <input type="radio" id="rating-4" name="ratingSurvey" value="2" /><label for="rating-4"></label>
                                <input type="radio" id="rating-5" name="ratingSurvey" value="1" /><label for="rating-5"></label>
                            </div>
                        </div>
                        <label class="hidden md:block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                            Jak oceniasz przygotowanie przychodni do przyjęcia osoby transpłciowej?
                        </label>
                    </div>
                </div>
                <div class="relative mt-4">
                    <textarea style="min-height: 60px" name="commentSurvey" id="commentSurvey" class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" placeholder="Komentarz"></textarea>
                    <label for="commentSurvey" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Komentarz
                    </label>
                </div>
                <div class="relative mt-3">
                    <input name="privacySurvey" id="privacySurvey" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="privacySurvey" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        <span class="font-bold">Wyrażam zgodę na przetwarzanie moich danych osobowych podanych w powyższym formularzu w celu zebrania statystyk nt. przeprowadzonego projektu przez Fundacja Kohezja NIP: 7812036316 *</span>
                    </label>
                </div>
                <div class="relative mt-2">
                    <input type="submit" class="block przycisk m-auto" value="Wyślij" />
                </div>
            </form>
        </div>
        <h2 class="mt-12 mb-4 text-indigo-600 dark:text-purple-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                <path d="M11.75 7.958V6.375q.646-.208 1.333-.302.688-.094 1.417-.094.479 0 .969.052.489.052 1.031.157v1.541q-.625-.146-1.104-.208-.479-.063-.896-.063-.708 0-1.396.125-.687.125-1.354.375Zm0 4.917v-1.583q.583-.188 1.25-.302.667-.115 1.5-.115.562 0 1.052.063.49.062.948.166v1.542q-.625-.146-1.104-.208-.479-.063-.896-.063-.708 0-1.396.125-.687.125-1.354.375Zm0-2.458V8.833q.667-.208 1.365-.312.697-.104 1.385-.104.562 0 1.052.062.49.063.948.167v1.542q-.542-.146-1.031-.209-.49-.062-.969-.062-.667 0-1.344.125t-1.406.375ZM5.5 14q.979 0 1.917.25.937.25 1.833.625V5.417q-.875-.459-1.812-.688Q6.5 4.5 5.5 4.5q-.771 0-1.531.135-.761.136-1.469.448V14.5q.729-.271 1.479-.385Q4.729 14 5.5 14Zm5.25.875q.896-.417 1.833-.646Q13.521 14 14.5 14q.771 0 1.531.094.761.094 1.469.406V5.083q-.729-.271-1.479-.427-.75-.156-1.521-.156-1 0-1.938.229-.937.229-1.812.688ZM10 17q-1.021-.667-2.146-1.083Q6.729 15.5 5.5 15.5q-.792 0-1.583.156-.792.156-1.521.469-.5.208-.948-.073Q1 15.771 1 15.229V4.75q0-.292.156-.542.156-.25.427-.375.938-.416 1.917-.625Q4.479 3 5.5 3q1.188 0 2.323.281T10 4.125q1.062-.542 2.188-.833Q13.312 3 14.5 3q1.021 0 2 .208.979.209 1.917.625.271.125.437.375.167.25.167.542v10.479q0 .521-.323.833-.323.313-.677.146-.833-.375-1.719-.541-.885-.167-1.802-.167-1.229 0-2.354.417Q11.021 16.333 10 17ZM5.896 9.688Z"></path>
            </svg>
            Bezpłatne materiały
        </h2>
        <div class="alert text-justify w-full md:w-4/6 m-auto p-8">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
            <form id='emails-poz-form'>
                <div class="relative">
                    <p id="response-message" class="hidden text-red-600 dark:text-red-300 font-bold block text-sm leading-5">Komunikat</p>
                </div>
                <div class="relative">
                    <input type="text" name="firstName" id="firstName" class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" placeholder="Imię" required>
                    <label for="firstName" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Imię
                    </label>
                </div>
                <div class="relative mt-4">
                    <input type="email" name="email" id="email" class="bg-gray-300 dark:bg-gray-900 form-input input-with-floating-label block w-full leading-5 rounded-md py-2 px-3 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out" placeholder="Adres e-mail" required>
                    <label for="email" class="block font-medium text-sm leading-5 absolute top-0 left-0 pl-3 pt-2 pointer-events-none transition duration-150 ease-in-out">
                        Adres e-mail
                    </label>
                </div>
                <div class="relative mt-5">
                    <input name="templateBook" id="templateBook" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="templateBook" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        <span>Chcę otrzymać na maila podręcznik dla lekarza POZ oraz wzór ulotek dla pacjenów</span>
                    </label>
                </div>
                <div class="relative mt-2">
                    <input name="templateTraining" id="templateTraining" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="templateTraining" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        <span>Chcę otrzymać na maila materiały wdrożeniowe do powielenia szkoleń</span>
                    </label>
                </div>
                <div class="relative mt-2">
                    <input name="delivery" id="delivery" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="delivery" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        Chcę otrzymać zestaw wydrukowanych materiałów i wyrażam zgodę na kontakt mailowy w tym celu
                    </label>
                </div>
                <div class="relative mt-3">
                    <input name="newsletter" id="newsletter" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="newsletter" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        <span>Chcę zapisać się na newsletter aby być na bieżąco z przyszłymi działaniami <a href="/">tranzycja.pl</a></span>
                    </label>
                </div>
                <div class="relative mt-3">
                    <input name="privacy" id="privacy" type="checkbox" class="hidden custom-checkbox-input">
                    <label for="privacy" class="inline-flex items-center cursor-pointer font-medium text-base">
                        <span class="relative w-5 h-5 border-2 border-gray-600 dark:border-gray-300 shadow-inner mr-2 flex-shrink-0">
                            <span class="absolute inset-0 h-full w-full flex items-center justify-center">
                                <svg class="h-4 w-4 fill-current dark:text-white" viewBox="0 0 24 24">
                                    <path d="m9.55 18-5.7-5.7 1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4Z" />
                                </svg>
                            </span>
                        </span>
                        <span class="font-bold">Wyrażam zgodę na przetwarzanie moich danych osobowych podanych w powyższym formularzu w celu otrzymania materiałów wypracowanym w ramach przeprowadzonego projektu przez Fundacja Kohezja NIP: 7812036316 *</span>
                    </label>
                </div>
                <div class="relative mt-2">
                    <input type="submit" class="block przycisk m-auto" value="Wyślij" />
                </div>
            </form>
        </div>
        <!--img class='w-2/4 m-auto mt-8' src='/media/img/poz-podrecznik.jpg' />-->
        <h2 class="mt-12 mb-4 text-indigo-600 dark:text-purple-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                <path d="M10,1.81A8.19,8.19,0,1,1,1.81,10,8.21,8.21,0,0,1,10,1.81m1.2,9.24A3.32,3.32,0,1,0,7.26,6.11l-.65,1,2,1.29.65-1A1,1,0,0,1,10,7a1,1,0,0,1,0,2H8.86V12.1H11.2Zm0,2.25H8.86v2.33H11.2Z"></path>
            </svg>
            Pytania i odpowiedzi
        </h2>
        <div class="faq-section float-none text-left w-full">
            <h2>Powielanie</h2>
            <details id="licencja">
                <summary>Na jakiej licencji udostępniane są materiały?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="powielanie">
                <summary>Jak powielać projekt?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="drukowanie">
                <summary>Gdzie wydrukować podręcznik/ulotkę?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <hr>
            <h2>Kontynuacja projektu</h2>
            <details id="bledy">
                <summary>Jak zgłaszać błędy?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="aktualizacje">
                <summary>Czy podręcznik będzie aktualizowany?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <hr>
            <h2>Dla lekarzy POZ</h2>
            <details id="dla-kogo-ulotka">
                <summary>Dla kogo jest ulotka, a dla kogo podręcznik?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="szkolenia">
                <summary>Czy zrobicie szkolenie w moim mieście?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="jak-dostac">
                <summary>Czy moje miejsce pracy może otrzymać podręcznik/ulotki?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
            <details id="jak-wykorzystac">
                <summary>Jak mogę wykorzystać zamieszczone materiały?</summary>
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In urna augue, fringilla ut leo nec, tempus vehicula risus. Sed in sem ligula. Nunc quis enim eu quam dignissim pretium. Fusce fermentum augue nec pretium semper. Donec a ex nulla. Integer bibendum volutpat dolor, in egestas velit venenatis eu. Proin pellentesque tempor neque, a commodo erat feugiat eget.</p>
                </div>
            </details>
        </div>
        <div class="clear-both"></div>
        <hr>
        <aside class="alert text-center">
            <h2>To co robimy jest dla Ciebie ważne?</h2>
            <p>Ten projekt istnieje dzięki zaangażowaniu takich osób jak Ty. Nawet drobne wsparcie od&nbsp;każdej osoby, która&nbsp;korzysta z&nbsp;naszej pracy, daje nam możliwość dalszego rozwoju i&nbsp;wspierania naszej społeczności.</p>
            <p><a href="/wsparcie" class="przycisk mb-1">Wesprzyj nas!</a></p>
        </aside>
    </div>
</main>
@endsection

@push('scripts')
<script defer="defer" src="{{ mix('js/poz/form-docs.js', 'assets/build') }}"></script>
<script defer="defer" src="{{ mix('js/poz/survey.js', 'assets/build') }}"></script>
@endpush