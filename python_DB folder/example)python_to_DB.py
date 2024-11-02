##import time
##import requests
##import mysql.connector
##import pymysql
##
### API 키와 URL 설정
##MOVIE_API_KEY = 'your_key'
##BOXOFFICE_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/boxoffice/searchDailyBoxOfficeList.json?key={MOVIE_API_KEY}&targetDt='
##MOVIE_INFO_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={MOVIE_API_KEY}&movieCd='
##
##def fetch_movie_info(movie_code):
##    try:
##        response = requests.get(MOVIE_INFO_API_URL + movie_code)
##        response.raise_for_status()
##        movie_info_data = response.json()['movieInfoResult']['movieInfo']
##
##        movie_info = {
##            'movieCd': movie_code,
##            'directors': [director['peopleNm'] for director in movie_info_data['directors']],
##            'actors': [actor['peopleNm'] for actor in movie_info_data['actors']],
##            'genres': [genre['genreNm'] for genre in movie_info_data['genres']],
##            'openDt': movie_info_data['openDt'] if 'openDt' in movie_info_data else None,
##            'showTm': movie_info_data['showTm'] if 'showTm' in movie_info_data else None,
##            'watchGradeNm': movie_info_data['audits'][0]['watchGradeNm'] if movie_info_data['audits'] else None
##        }
##
##        return movie_info
##    except Exception as e:
##        print(f"An error occurred while fetching movie info for {movie_code}: {e}")
##        return None
##
##def fetch_boxoffice_data():
##    try:
##        yesterday = time.strftime('%Y%m%d', time.localtime(time.time() - 86400))  # 어제 날짜로 데이터 가져오기
##        response = requests.get(BOXOFFICE_API_URL + yesterday)
##        response.raise_for_status()
##        boxoffice_data = response.json()['boxOfficeResult']['dailyBoxOfficeList']
##
##        detailed_boxoffice_data = []
##        for movie in boxoffice_data:
##            movie_code = movie['movieCd']
##            movie_info = fetch_movie_info(movie_code)
##            if movie_info:
##                detailed_boxoffice_data.append({
##                    'rank': movie['rank'],
##                    'movieCd': movie_code,
##                    'movieNm': movie['movieNm'],
##                    'openDt': movie_info['openDt'],
##                    'showTm': movie_info['showTm'],
##                    'watchGradeNm': movie_info['watchGradeNm'],
##                    'directors': ', '.join(movie_info['directors']),
##                    'actors': ', '.join(movie_info['actors']),
##                    'genres': ', '.join(movie_info['genres']),
##                })
##
##        return detailed_boxoffice_data
##    except Exception as e:
##        print(f"An error occurred while fetching box office data: {e}")
##        return []
##
##def save_to_database(data):
##    try:
##        conn = mysql.connector.connect(
##            host='your_host',
##            port=your_port,
##            user='your_id',
##            password='your_pw',
##            database='your_db_name'
##        )
##        #conn = pymysql.connect(host = "codingmaker.net", port = 33060,  user = "khs0624", password = "0624", db = "khs0624", charset ="utf8") 
##        cursor = conn.cursor()
##
##        for movie in data:
##            cursor.execute('''
##                INSERT INTO movie (
##                    movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age
##                ) VALUES (%s, %s, %s, %s, %s, %s, %s)
##                ON DUPLICATE KEY UPDATE
##                    movie_title=VALUES(movie_title), movie_character=VALUES(movie_character), movie_genre=VALUES(movie_genre),
##                    movie_Date=VALUES(movie_Date), movie_running=VALUES(movie_running), movie_age=VALUES(movie_age)
##                    
##            ''', (
##                movie['movieCd'], movie['movieNm'], movie['directors'] + ', ' + movie['actors'], movie['genres'], 
##                movie['openDt'], movie['showTm'], movie['watchGradeNm']
##            ))
##
##        conn.commit()
##        conn.close()
##    except Exception as e:
##        print(f"An error occurred while saving data to the database: {e}")
##
##def update_data_periodically():
##    while True:
##        boxoffice_data = fetch_boxoffice_data()
##        if boxoffice_data:
##            save_to_database(boxoffice_data)
##        time.sleep(43200)  # 12시간마다 데이터 업데이트
##
##if __name__ == '__main__':
##    update_data_periodically()

import time
import requests
import mysql.connector

# API 키와 URL 설정
MOVIE_API_KEY = 'your_key'
BOXOFFICE_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/boxoffice/searchDailyBoxOfficeList.json?key={MOVIE_API_KEY}&targetDt='
MOVIE_INFO_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={MOVIE_API_KEY}&movieCd='

def fetch_movie_info(movie_code):
    try:
        response = requests.get(MOVIE_INFO_API_URL + movie_code)
        response.raise_for_status()
        movie_info_data = response.json()['movieInfoResult']['movieInfo']

        movie_info = {
            'movieCd': movie_code,
            'directors': [director['peopleNm'] for director in movie_info_data['directors']],
            'actors': [actor['peopleNm'] for actor in movie_info_data['actors']],
            'genres': [genre['genreNm'] for genre in movie_info_data['genres']],
            'openDt': movie_info_data['openDt'] if 'openDt' in movie_info_data else None,
            'showTm': movie_info_data['showTm'] if 'showTm' in movie_info_data else None,
            'watchGradeNm': movie_info_data['audits'][0]['watchGradeNm'] if movie_info_data['audits'] else None
        }

        return movie_info
    except Exception as e:
        print(f"An error occurred while fetching movie info for {movie_code}: {e}")
        return None

def fetch_boxoffice_data():
    try:
        yesterday = time.strftime('%Y%m%d', time.localtime(time.time() - 86400))  # 어제 날짜로 데이터 가져오기
        response = requests.get(BOXOFFICE_API_URL + yesterday)
        response.raise_for_status()
        boxoffice_data = response.json()['boxOfficeResult']['dailyBoxOfficeList']

        detailed_boxoffice_data = []
        for movie in boxoffice_data:
            movie_code = movie['movieCd']
            movie_info = fetch_movie_info(movie_code)
            if movie_info:
                detailed_boxoffice_data.append({
                    'rank': movie['rank'],
                    'movieCd': movie_code,
                    'movieNm': movie['movieNm'],
                    'openDt': movie_info['openDt'],
                    'showTm': movie_info['showTm'],
                    'watchGradeNm': movie_info['watchGradeNm'],
                    'directors': ', '.join(movie_info['directors']),
                    'actors': ', '.join(movie_info['actors']),
                    'genres': ', '.join(movie_info['genres']),
                })

        return detailed_boxoffice_data
    except Exception as e:
        print(f"An error occurred while fetching box office data: {e}")
        return []

def save_to_database(data):
    try:
        # 데이터베이스 연결
        conn = mysql.connector.connect(
##            host='your_host',
##            port=your_port,
##            user='your_id',
##            password='your_pw',
##            database='your_db_name'
        )
        cursor = conn.cursor()

        # temp_movie 테이블에 데이터 삽입
        cursor.execute("DELETE FROM temp_movie")
        for movie in data:
            cursor.execute('''
                INSERT INTO temp_movie (
                    movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age, ranking
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            ''', (
                movie['movieCd'], movie['movieNm'], movie['directors'] + ', ' + movie['actors'], movie['genres'], 
                movie['openDt'], movie['showTm'], movie['watchGradeNm'], movie['rank']
            ))

        # 중복되는 순위를 가진 기존 데이터 삭제
        cursor.execute('''
            DELETE m1 FROM movie m1
            INNER JOIN (
                SELECT ranking
                FROM movie
                GROUP BY ranking
                HAVING COUNT(*) > 1
            ) m2 ON m1.ranking = m2.ranking
        ''')

        # 기존 movie 테이블에서 temp_movie와 같은 영화 코드가 있는 경우 순위 업데이트
        cursor.execute('''
            UPDATE movie m
            JOIN temp_movie tm ON m.movie_num = tm.movie_num
            SET m.ranking = tm.ranking
        ''')

        # 기존 movie 테이블에서 temp_movie와 같은 순위를 가진 다른 영화 코드가 있는 경우 삭제
        cursor.execute('''
            DELETE m1 FROM movie m1
            INNER JOIN (
                SELECT m.movie_num
                FROM movie m
                JOIN temp_movie tm ON m.ranking = tm.ranking
                WHERE m.movie_num != tm.movie_num
            ) m2 ON m1.movie_num = m2.movie_num
        ''')

        # temp_movie 데이터를 movie 테이블에 삽입, 기존 영화 정보는 그대로 유지
        cursor.execute('''
            INSERT INTO movie (
                movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age, ranking
            )
            SELECT 
                tm.movie_num, tm.movie_title, tm.movie_character, tm.movie_genre, tm.movie_Date, tm.movie_running, tm.movie_age, tm.ranking
            FROM temp_movie tm
            ON DUPLICATE KEY UPDATE
                ranking = VALUES(ranking)
        ''')

        # 10위 이후의 영화 삭제
        cursor.execute('DELETE FROM movie WHERE ranking > 10')

        conn.commit()
    except mysql.connector.Error as e:
        print(f"Error: {e}")
    finally:
        cursor.close()
        conn.close()



def update_data_periodically():
    while True:
        boxoffice_data = fetch_boxoffice_data()
        if boxoffice_data:
            save_to_database(boxoffice_data)
        time.sleep(43200)  # 12시간마다 데이터 업데이트

if __name__ == '__main__':
    update_data_periodically()




