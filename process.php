<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';
    $sort = isset($_POST['sort']) ? $_POST['sort'] : '';
    $limit = isset($_POST['limit']) ? $_POST['limit'] : '';

    $errors = [];

    // 1. Validate the text input
    if (empty($text)) {
        $errors[] = "The text area cannot be empty.";
    }

    // 2. Validate the sort option
    if ($sort !== 'asc' && $sort !== 'desc') {
        $errors[] = "Invalid sort option. Please select 'Ascending' or 'Descending'.";
    }

    // 3. Validate the limit input (must be an integer and greater than or equal to 1)
    if (!filter_var($limit, FILTER_VALIDATE_INT, options: ["options" => ["min_range" => 1]])) {
        $errors[] = "The limit must be a positive integer greater than or equal to 1.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }

    } else {

        function tokenizeText(string $text)
        {
            $text = strtolower($text);

            $tokens = preg_split('/[\s\W]+/', $text, -1, PREG_SPLIT_NO_EMPTY);

            return $tokens;
        }


        function sliceArray(array $array, int $limit): array
        {
            if ($limit < 0) {
                throw new InvalidArgumentException("Limit must be a non-negative integer.");
            }

            return array_slice($array, 0, $limit);
        }


        function filterTokens(array $tokens, array $wordsToRemove): array
        {
            $filteredTokens = array_filter($tokens, function ($token) use ($wordsToRemove) {
                return !in_array(strtolower($token), array_map('strtolower', $wordsToRemove));
            });

            return $filteredTokens;
        }


        function countWordFrequencies(array $tokens)
        {
            $wordFrequencies = [];

            foreach ($tokens as $word) {
                if (isset($wordFrequencies[$word])) {
                    $wordFrequencies[$word]++;
                } else {
                    $wordFrequencies[$word] = 1;
                }
            }

            return $wordFrequencies;
        }

        function sortWordFrequencies(array $wordFrequencies, string $sort)
        {
            if ($sort === 'asc') {
                asort($wordFrequencies);
            } elseif ($sort === 'desc') {
                arsort($wordFrequencies);
            } else {
                throw new InvalidArgumentException("Invalid sort order: $sort. Use 'asc' or 'desc'.");
            }

            return $wordFrequencies;
        }


        $STOPWORDS = [
            'a',
            'about',
            'above',
            'after',
            'again',
            'against',
            'all',
            'am',
            'an',
            'and',
            'any',
            'are',
            'aren\'t',
            'as',
            'at',
            'be',
            'because',
            'been',
            'before',
            'being',
            'below',
            'between',
            'both',
            'but',
            'by',
            'can',
            'couldn\'t',
            'did',
            'didn\'t',
            'do',
            'does',
            'doesn\'t',
            'doing',
            'don\'t',
            'down',
            'during',
            'each',
            'few',
            'for',
            'from',
            'further',
            'had',
            'hadn\'t',
            'has',
            'hasn\'t',
            'have',
            'haven\'t',
            'having',
            'he',
            'he\'d',
            'he\'ll',
            'he\'s',
            'her',
            'here',
            'here\'s',
            'hers',
            'herself',
            'him',
            'himself',
            'his',
            'how',
            'how\'s',
            'i',
            'i\'d',
            'i\'ll',
            'i\'m',
            'i\'ve',
            'if',
            'in',
            'into',
            'is',
            'isn\'t',
            'it',
            'it\'s',
            'its',
            'itself',
            'let\'s',
            'me',
            'more',
            'most',
            'mustn\'t',
            'my',
            'myself',
            'no',
            'nor',
            'not',
            'of',
            'off',
            'on',
            'once',
            'only',
            'or',
            'other',
            'ought',
            'our',
            'ours',
            'ourselves',
            'out',
            'over',
            'own',
            'same',
            'shan\'t',
            'she',
            'she\'d',
            'she\'ll',
            'she\'s',
            'should',
            'shouldn\'t',
            'so',
            'some',
            'such',
            'than',
            'that',
            'that\'s',
            'the',
            'their',
            'theirs',
            'them',
            'themselves',
            'then',
            'there',
            'there\'s',
            'these',
            'they',
            'they\'d',
            'they\'ll',
            'they\'re',
            'they\'ve',
            'this',
            'those',
            'through',
            'to',
            'too',
            'under',
            'until',
            'up',
            'very',
            'was',
            'wasn\'t',
            'we',
            'we\'d',
            'we\'ll',
            'we\'re',
            'we\'ve',
            'were',
            'weren\'t',
            'what',
            'what\'s',
            'when',
            'when\'s',
            'where',
            'where\'s',
            'which',
            'while',
            'who',
            'who\'s',
            'whom',
            'why',
            'why\'s',
            'with',
            'won\'t',
            'would',
            'wouldn\'t',
            'you',
            'you\'d',
            'you\'ll',
            'you\'re',
            'you\'ve',
            'your',
            'yours',
            'yourself',
            'yourselves'
        ];

        $tokens = tokenizeText($text);

        $filtered_tokens = filterTokens($tokens, $STOPWORDS);

        $word_frequencies = countWordFrequencies($filtered_tokens);

        $sorted_word_frequencies = sortWordFrequencies($word_frequencies, $sort);

        $sliced_word_frequencies = sliceArray($sorted_word_frequencies, $limit);

        echo json_encode($sliced_word_frequencies);
    }
}