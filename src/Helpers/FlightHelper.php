<?php

if (!function_exists('travelers')) {

    /**
     * get the list of travelers with their number incremented by type
     *
     *
     * @return array
     *
     * @author Mehdi Ait Mouh <mehdi.aitmouh.dev@gmail.com>
     * @author Ibtissam Toujni <Btissamtoujni@gmail.com>
     */

    function travelers(int $adults, int $childrens, int $infants) {

        $count = 0;

        $travelers = [];

        $travelers[0] = [];

        $adultCount = 0;

        for ($adult = 1; $adult <= $adults; $adult++) {

            array_push($travelers[0], array(

                'id' => $adult,
                'travelerType' => 'ADULT',
                'fareOptions' => ['STANDARD']

            ));

            $count++;

            $adultCount++;
        }

        for ($child = 1; $child <= $childrens; $child++) {

            array_push($travelers[0], array(

                'id' => $count + 1,
                'travelerType' => 'CHILD',
                'fareOptions' => ['STANDARD']

            ));

            $count++;
        }

        for ($inf = 1; $inf <= $infants; $inf++) {

            array_push($travelers[0], array(

                'id' => $count + 1,
                'travelerType' => 'HELD_INFANT',
                'fareOptions' => ['STANDARD'],
                'associatedAdultId' => (string) ($adultCount > 1 ? ($inf) : 1)

            ));

            $count++;
        }

        return $travelers[0];
    }
}